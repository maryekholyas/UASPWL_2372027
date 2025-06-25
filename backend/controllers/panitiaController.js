const Event = require('../models/Event');
const Registration = require('../models/Registration');

const createEvent = async (req, res) => {
  try {
    const {
      judul,
      deskripsi,
      tanggal,
      lokasi,
      kapasitas,
      durasi,
      biaya,
      sesi,
      poster
    } = req.body;

    let sesiArray = [];
    if (sesi) {
      try {
        sesiArray = typeof sesi === 'string' ? JSON.parse(sesi) : sesi;
      } catch (parseError) {
        return res.status(400).json({ message: 'Format sesi tidak valid' });
      }
    }

    const newEvent = await Event.create({
      judul,
      deskripsi,
      tanggal,
      lokasi,
      kapasitas,
      poster: poster ?? null, 
      sesi: sesiArray,
      durasi,
      biaya,
      pendaftar: [],
      status: 'upcoming',
      panitia: [req.user._id]
    });

    res.status(201).json({
      message: 'Event berhasil dibuat',
      event: newEvent
    });
  } catch (error) {
    res.status(500).json({ message: 'Gagal membuat event', error: error.message });
  }
};

const getEvents = async (req, res) => {
  try {
    const events = await Event.find().lean();
    for (let event of events) {
      const registrations = await Registration.find({ event: event._id, status: { $in: ['approved', 'paid', 'checked_in', 'waiting_approval'] } })
        .populate('user', 'nama email _id')
        .lean();
      event.pendaftar = registrations.map(reg => ({
        ...reg.user,
        hadir: reg.hadir,
        sertifikat: reg.sertifikat 
      }));
    }

    res.json({
      message: 'Daftar event berhasil diambil',
      events
    });
  } catch (error) {
    res.status(500).json({ message: 'Gagal mengambil daftar event', error: error.message });
  }
};

const getEventById = async (req, res) => {
  try {
    const { id } = req.params;
    const event = await Event.findById(id);
    
    if (!event) {
      return res.status(404).json({ message: 'Event tidak ditemukan' });
    }
    
    res.json({
      message: 'Event berhasil ditemukan',
      event
    });
  } catch (error) {
    res.status(500).json({ message: 'Gagal mendapatkan event', error: error.message });
  }
};

const updateEvent = async (req, res) => {
  try {
    const { id } = req.params;
    const updateData = req.body;
    if (req.file) {
      updateData.poster = req.file.path;
    }
    
    const updatedEvent = await Event.findByIdAndUpdate(id, updateData, { new: true });
    
    if (!updatedEvent) {
      return res.status(404).json({ message: 'Event tidak ditemukan' });
    }
    
    res.json({
      message: 'Event berhasil diperbarui',
      event: updatedEvent
    });
  } catch (error) {
    res.status(500).json({ message: 'Gagal mengupdate event', error: error.message });
  }
};

const deleteEvent = async (req, res) => {
  try {
    const { id } = req.params;
    const event = await Event.findByIdAndDelete(id);
    
    if (!event) {
      return res.status(404).json({ message: 'Event tidak ditemukan' });
    }
    
    res.json({ message: 'Event berhasil dihapus' });
  } catch (error) {
    res.status(500).json({ message: 'Gagal menghapus event', error: error.message });
  }
};

const scanAttendance = async (req, res) => {
  try {
    const { kodeAbsensi } = req.body;
    const registration = await Registration.findOne({ kodeAbsensi }).populate('user').populate('event');

    if (!registration) {
      return res.status(404).json({ message: 'Kode absensi tidak ditemukan' });
    }

    if (registration.hadir) {
      return res.status(400).json({ message: 'Peserta sudah check-in sebelumnya' });
    }

    registration.hadir = true;
    registration.checkInAt = new Date();
    await registration.save();

    res.status(200).json({
      message: 'Check-in berhasil',
      peserta: {
        nama: registration.user.nama,
        email: registration.user.email
      },
      event: {
        judul: registration.event.judul,
        tanggal: registration.event.tanggal
      },
      waktu: registration.checkInAt
    });
  } catch (error) {
    res.status(500).json({ message: 'Gagal melakukan check-in', error: error.message });
  }
};

const uploadCertificate = async (req, res) => {
  try {
    const { eventId, participantId, sertifikat } = req.body;

    if (!sertifikat || typeof sertifikat !== 'string') {
      return res.status(400).json({ message: 'Link sertifikat tidak valid' });
    }

    const registration = await Registration.findOne({ event: eventId, user: participantId });
    if (!registration) {
      return res.status(404).json({ message: 'Registrasi peserta tidak ditemukan' });
    }
    if (!registration.hadir) {
      return res.status(400).json({ message: 'Sertifikat hanya bisa diupload jika peserta sudah absen (hadir).' });
    }

    registration.sertifikat = sertifikat;
    await registration.save();

    res.json({
      message: 'Sertifikat berhasil diupload',
      sertifikat: registration.sertifikat
    });
  } catch (error) {
    res.status(500).json({ message: 'Gagal mengupload sertifikat', error: error.message });
  }
};

const getRegistrationsByEvent = async (req, res) => {
  try {
    const { eventId } = req.query;
    if (!eventId) {
      return res.status(400).json({ message: 'eventId wajib diisi' });
    }

    const registrations = await Registration.find({ event: eventId })
      .populate('user', 'nama email _id')
      .lean();

    res.json({
      message: 'Daftar registrasi berhasil diambil',
      registrations
    });
  } catch (error) {
    res.status(500).json({ message: 'Gagal mengambil daftar registrasi', error: error.message });
  }
};

module.exports = {
  createEvent,
  getEvents,
  getEventById,
  updateEvent,
  deleteEvent,
  scanAttendance,
  uploadCertificate,
  getRegistrationsByEvent
};