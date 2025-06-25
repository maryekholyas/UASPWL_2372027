const User = require('../models/User');
const jwt = require('jsonwebtoken');

exports.register = async (req, res) => {
    try {
        const { nama, email, password, role } = req.body;

        if (!nama || !email || !password) {
            return res.status(400).json({ message: 'Semua field wajib diisi.' });
        }

        if (await User.findOne({ $or: [{ email }] })) {
            return res.status(400).json({ message: 'Email sudah terdaftar.' });
        }

        const newUser = await User.create({ nama, email, password, role });
        const token = jwt.sign({ id: newUser._id, role: newUser.role }, process.env.JWT_SECRET);

        res.status(201).json({
            message: 'Registrasi berhasil.',
            user: { ...newUser.toObject(), password: undefined },
            token
        });
    } catch (error) {
        res.status(500).json({ message: 'Terjadi kesalahan server saat registrasi.' });
    }
};

exports.login = async (req, res) => {
    try {
        const { email, password } = req.body;
        const user = await User.findOne({ email });

        if (!user || !(await user.comparePassword(password))) {
            return res.status(401).json({ message: 'Email atau password salah' });
        }

        const token = jwt.sign({ id: user._id, role: user.role }, process.env.JWT_SECRET, { expiresIn: '24h' });
        const roleEndpoints = {
            administrator: '/api/auth/admin',
            tim_keuangan: '/api/auth/finance', 
            panitia: '/api/auth/committee',
            member: '/api/auth/member'
        };

        res.json({
            message: 'Login berhasil',
            user: { ...user.toObject(), password: undefined },
            token,
            redirectTo: roleEndpoints[user.role]
        });
    } catch (error) {
        res.status(500).json({ message: 'Terjadi kesalahan server' });
    }
};
