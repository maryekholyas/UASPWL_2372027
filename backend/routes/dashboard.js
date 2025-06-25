const express = require('express');
const router = express.Router();
const { protect } = require('../middleware/authMiddleware');
const { checkRole } = require('../middleware/roleCheck');
const User = require('../models/User');
const Event = require('../models/Event');
// untuk get method admin
const { getAllStaff, getDashboardStats, addStaff, updateStaff, deleteStaff } = require('../controllers/adminController');
// untuk panit
const {createEvent, getEvents, getEventById, updateEvent, deleteEvent, scanAttendance, uploadCertificate, getRegistrationsByEvent} = require('../controllers/panitiaController');
// untuk tim keuangan
const { updatePaymentStatus, getFinanceStats, getAllRegistrations } = require('../controllers/keuanganController');
// untuk member
const { registerToEvent, uploadBuktiPembayaran, getMyRegistrations, getMemberDashboard } = require('../controllers/memberController');

// Route untuk administrator
router.get('/admin', protect, checkRole(['administrator']), getDashboardStats);
router.get('/admin/staff', protect, checkRole(['administrator']), getAllStaff);
router.post('/admin/staff', protect, checkRole(['administrator']), addStaff);
router.put('/admin/staff/:id', protect, checkRole(['administrator']), updateStaff);
router.delete('/admin/staff/:id', protect, checkRole(['administrator']), deleteStaff);

// Route untuk Panitia
router.post('/event', protect, checkRole(['panitia']), createEvent);
router.get('/event', protect, checkRole(['panitia']), getEvents);
router.get('/event/:id', protect, checkRole(['panitia']), getEventById);
router.put('/event/:id', protect, checkRole(['panitia']), updateEvent);
router.delete('/event/:id', protect, checkRole(['panitia']), deleteEvent);
router.post('/event/scan', protect, checkRole(['panitia']), scanAttendance);
router.post('/event/certificate', protect, checkRole(['panitia']), uploadCertificate);
router.get('/event/registrations', protect, checkRole(['panitia']), getRegistrationsByEvent);

// Route untuk Tim Keuangan
router.put('/finance/payment-status', protect, checkRole(['tim_keuangan']), updatePaymentStatus);
router.get('/finance/stats', protect, checkRole(['tim_keuangan']), getFinanceStats);
router.get('/finance/registrations', protect, checkRole(['tim_keuangan']), getAllRegistrations);


// ================== MEMBER ROUTES ==================
router.post('/member/register/:eventId', protect, checkRole(['member']), registerToEvent);
router.post('/member/upload-bukti/:registrationId', protect, checkRole(['member']), uploadBuktiPembayaran);
router.get('/member/my-registrations', protect, checkRole(['member']), getMyRegistrations);
router.get('/member/dashboard', protect, checkRole(['member']), getMemberDashboard);

module.exports = router;