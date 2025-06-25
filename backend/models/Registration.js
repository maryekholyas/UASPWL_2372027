const mongoose = require('mongoose');

const registrationSchema = new mongoose.Schema({
  user: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'User',
    required: true
  },
  event: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Event',
    required: true
  },
  kodePembayaran: {
    type: String,
    required: true,
    unique: true
  },
  status: {
    type: String,
    default: 'pending'
  },
  buktiBayar: {
    type: String,
    default: null
  },
  kodeAbsensi: {
    type: String,
    default: null
  },
  waktuDaftar: {
    type: Date,
    default: Date.now
  },
  waktuVerifikasi: {
    type: Date
  },
  hadir: {
    type: Boolean,
    default: false
  },
  checkInAt: {
    type: Date,
    default: null
  },
  sertifikat: {
    type: String,
    default: null
  }
});

module.exports = mongoose.model('Registration', registrationSchema);
