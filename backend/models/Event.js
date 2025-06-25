const mongoose = require('mongoose');

const eventSchema = new mongoose.Schema({
    judul: {
        type: String,
        required: true
    },
    deskripsi: {
        type: String,
        required: true
    },
    tanggal: {
        type: Date,
        required: true
    },
    lokasi: {
        type: String,
        required: true
    },
    kapasitas: {
        type: Number,
        required: true
    },
    poster: {
        type: String
    },
    sesi: [{
        nama: { type: String },
        waktuMulai: { type: Date },
        waktuSelesai: { type: Date },
        keterangan: { type: String }
    }],
    durasi: {
        type: Number,
    },
    pendaftar: [{
        user: {
            type: mongoose.Schema.Types.ObjectId,
            ref: 'User'
        },
        statusPembayaran: {
            type: String,
            default: 'pending'
        }
    }],
    status: {
        type: String,
        default: 'upcoming'
    },
    biaya: {
        type: Number,
        required: true
    },
    panitia: [{
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User'
    }],
    createdAt: {
        type: Date,
        default: Date.now
    }
});

module.exports = mongoose.model('Event', eventSchema);
