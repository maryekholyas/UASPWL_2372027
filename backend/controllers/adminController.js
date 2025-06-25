const User = require('../models/User');
const Event = require('../models/Event');

const getDashboardStats = async (req, res) => {
    try {
        const totalUsers = await User.countDocuments();
        const totalEvents = await Event.countDocuments();
        const totalStaff = await User.countDocuments({
            role: { $in: ['tim_keuangan', 'panitia','member'] }
        });
        
        res.json({
            message: 'Selamat datang di Dashboard Administrator',
            user: req.user,
            stats: {
                totalUsers,
                totalEvents,
                totalStaff
            }
        });
    } catch (error) {
        res.status(500).json({ message: 'Terjadi kesalahan server' });
    }
};

const getAllStaff = async (req, res) => {
    try {
        const staff = await User.find({
            role: { $in: ['tim_keuangan', 'panitia','member'] }
        }).select('-password');

        res.json({
            message: 'Daftar staff berhasil diambil',
            staff
        });
    } catch (error) {
        res.status(500).json({ message: 'Gagal mengambil daftar staff' });
    }
};

const addStaff = async (req, res) => {
    try {
        const { nama, email, password, role } = req.body;

        if (!['tim_keuangan', 'panitia', 'member'].includes(role)) {
            return res.status(400).json({ message: 'Role tidak valid' });
        }

        const staff = new User({
            nama,
            email,
            password,
            role
        });

        await staff.save();
        const staffData = await User.findById(staff._id).select('-password');

        res.status(201).json({
            message: 'Staff baru berhasil ditambahkan',
            staff: staffData
        });
    } catch (error) {
        res.status(500).json({ message: 'Gagal menambahkan staff' });
    }
};

const updateStaff = async (req, res) => {
    try {
        const { id } = req.params;
        const { nama, email, password, role } = req.body;

        if (!['tim_keuangan', 'panitia', 'member'].includes(role)) {
            return res.status(400).json({ message: 'Role tidak valid' });
        }

        let updateData = { nama, email, role };

        if (typeof password === 'string' && password.trim() !== "") {
            updateData.password = password;
        }

        const user = await User.findByIdAndUpdate(id, updateData, { new: true }).select('-password');

        if (!user) {
            return res.status(404).json({ message: 'Data tidak ditemukan' });
        }

        res.json({
            message: `Data ${role === 'member' ? 'member' : 'staff'} berhasil diperbarui`,
            user
        });
    } catch (error) {
        res.status(500).json({ message: 'Gagal memperbarui data staff' });
    }
};

const deleteStaff = async (req, res) => {
    try {
        const { id } = req.params;

        const staff = await User.findByIdAndDelete(id);

        if (!staff) {
            return res.status(404).json({ message: 'Staff tidak ditemukan' });
        }

        res.json({ message: 'Staff berhasil dihapus' });
    } catch (error) {
        res.status(500).json({ message: 'Gagal menghapus staff' });
    }
};

module.exports = {
    getDashboardStats,
    getAllStaff,
    addStaff,
    updateStaff,
    deleteStaff
};