const { v4: uuidv4 } = require('uuid');
const Event = require('../models/Event');
const Registration = require('../models/Registration');

const updatePaymentStatus = async (req, res) => {
  try {
    const { kodePembayaran, paymentStatus } = req.body; 

    const registration = await Registration.findOne({ kodePembayaran });
    if (!registration) {
      return res.status(404).json({ message: 'Pendaftaran dengan kode pembayaran tersebut tidak ditemukan' });
    }

    registration.status = paymentStatus;

    if (paymentStatus === 'paid' && registration.buktiBayar) {
      if (!registration.kodeAbsensi) {
        registration.kodeAbsensi = `ABS-${uuidv4()}`;
      }
    }

    await registration.save();

    res.json({
      message: 'Status pembayaran peserta berhasil diupdate',
      registration
    });
  } catch (error) {
    res.status(500).json({ message: 'Gagal mengupdate status pembayaran', error: error.message });
  }
};

const getFinanceStats = async (req, res) => {
  try {
    const events = await Event.find();

    let totalRevenue = 0;
    let totalPaidParticipants = 0;
    let totalPendingParticipants = 0;

    events.forEach(event => {
      const paidCount = event.pendaftar.filter(p => p.statusPembayaran === 'paid').length;
      const pendingCount = event.pendaftar.filter(p => p.statusPembayaran === 'pending').length;

      totalRevenue += paidCount * event.biaya;
      totalPaidParticipants += paidCount;
      totalPendingParticipants += pendingCount;
    });

    res.json({
      message: 'Statistik keuangan berhasil diambil',
      stats: {
        totalRevenue,
        totalPaidParticipants,
        totalPendingParticipants,
        totalEvents: events.length
      }
    });
  } catch (error) {
    res.status(500).json({ message: 'Gagal mengambil statistik keuangan', error: error.message });
  }
};

const getAllRegistrations = async (req, res) => {
  try {
    const registrations = await Registration.find()
      .populate('user', 'nama email')
      .populate('event', 'judul');
    res.json({
      message: 'Daftar registrasi berhasil diambil',
      registrations
    });
  } catch (error) {
    res.status(500).json({ message: 'Gagal mengambil daftar registrasi', error: error.message });
  }
};

module.exports = { updatePaymentStatus, getFinanceStats, getAllRegistrations };