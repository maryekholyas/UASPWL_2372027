const Event = require('../models/Event');

exports.getAllEvents = async (req, res) => {
    try {
        const events = await Event.find().sort({ tanggal: 1 });

        res.status(200).json({
            message: 'Berhasil mendapatkan daftar event',
            events
        });
    } catch (error) {
        res.status(500).json({
            message: 'Terjadi kesalahan server',
            error: error.message
        });
    }
};

exports.getEventById = async (req, res) => {
    try {
        const event = await Event.findById(req.params.id)
            .populate('pendaftar', 'nama email nim')
            .populate('panitia', 'nama email');

        if (!event) {
            return res.status(404).json({ message: 'Event tidak ditemukan' });
        }

        res.status(200).json({
            message: 'Berhasil mendapatkan detail event',
            event
        });
    } catch (error) {
        res.status(500).json({
            message: 'Terjadi kesalahan server',
            error: error.message
        });
    }
};
