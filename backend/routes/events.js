const express = require('express');
const router = express.Router();
const { getAllEvents, getEventById, createDummyEvents } = require('../controllers/eventController');

// Route untuk mendapatkan semua event
router.get('/', getAllEvents);

// Route untuk mendapatkan detail event
router.get('/:id', getEventById);

module.exports = router; 