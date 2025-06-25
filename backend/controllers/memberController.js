const Registration = require('../models/Registration');
const Event = require('../models/Event');
const { v4: uuidv4 } = require('uuid');

exports.getMemberDashboard = async (req, res) => {
  try {
    const userId = req.user._id;

    const registrations = await Registration.find({ user: userId })
      .populate('event', 'judul tanggal lokasi biaya')
      .sort({ waktuDaftar: -1 }); 

    const totalEvent = registrations.length;
    const approved = registrations.filter(r => r.status === 'approved').length;
    const pending = registrations.filter(r => r.status === 'pending' || r.status === 'waiting_approval').length;
    const checkedIn = registrations.filter(r => r.status === 'checked_in').length;

    res.status(200).json({
      message: 'Data dashboard member berhasil diambil',
      user: {
        id: req.user._id,
        name: req.user.name,
        email: req.user.email
      },
      stats: {
        totalEvent,
        approved,
        pending,
        checkedIn
      },
      registrations
    });
  } catch (error) {
    res.status(500).json({ message: 'Gagal mengambil dashboard', error: error.message });
  }
};

exports.registerToEvent = async (req, res) => {
  try {
    const { eventId } = req.params;
    const userId = req.user._id;
    const { buktiBayar } = req.body;

    const existing = await Registration.findOne({ user: userId, event: eventId });
    if (existing) {
      return res.status(400).json({ message: 'Anda sudah mendaftar pada event ini.' });
    }

    const paymentCode = `PAY-${uuidv4()}`;

    const registration = await Registration.create({
      user: userId,
      event: eventId,
      kodePembayaran: paymentCode,
      buktiBayar: buktiBayar || null
    });

    res.status(201).json({
      message: 'Pendaftaran berhasil. Silakan lakukan pembayaran.',
      registration
    });
  } catch (error) {
    res.status(500).json({ message: 'Gagal mendaftar', error: error.message });
  }
};

exports.uploadBuktiPembayaran = async (req, res) => {
  try {
    const { registrationId } = req.params;
    const { buktiBayar } = req.body;

    if (!buktiBayar || typeof buktiBayar !== 'string') {
      return res.status(400).json({ message: 'Link bukti pembayaran tidak valid' });
    }

    const updated = await Registration.findByIdAndUpdate(
      registrationId,
      {
        buktiBayar,
        status: 'waiting_approval'
      },
      { new: true }
    );

    res.status(200).json({
      message: 'Bukti pembayaran berhasil dikirim, menunggu verifikasi.',
      registration: updated
    });
  } catch (error) {
    res.status(500).json({ message: 'Gagal mengirim bukti', error: error.message });
  }
};

exports.getMyRegistrations = async (req, res) => {
  try {
    const userId = req.user._id;

    const registrations = await Registration.find({ user: userId })
      .populate('event', 'judul tanggal lokasi');

    res.status(200).json({
      message: 'Daftar event yang Anda ikuti',
      registrations
    });
  } catch (error) {
    res.status(500).json({ message: 'Gagal mengambil data', error: error.message });
  }
};
