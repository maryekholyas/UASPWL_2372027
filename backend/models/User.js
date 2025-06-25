const mongoose = require('mongoose');
const bcrypt = require('bcryptjs');

const userSchema = new mongoose.Schema({
    nama: {
        type: String,
        required: true
    },
    email: {
        type: String,
        required: true,
        unique: true
    },
    password: {
        type: String,
        required: true
    },
    role: {
        type: String,
        default: 'guest'
    },
    joinedEvents: [{
        event: {
            type: mongoose.Schema.Types.ObjectId,
            ref: 'Event'
        },
        status: {
            type: String,
            default: 'pending'
        },
        joinedAt: {
            type: Date,
            default: Date.now
        }
    }],
    createdAt: {
        type: Date,
        default: Date.now
    }
});

userSchema.pre('save', async function(next) {
    if (!this.isModified('password')) return next();
    this.password = await bcrypt.hash(this.password, 10);
    next();
});

userSchema.methods.comparePassword = async function(candidatePassword) {
    return await bcrypt.compare(candidatePassword, this.password);
};

userSchema.methods.joinEvent = async function(eventId) {
    if (this.joinedEvents.some(je => je.event.toString() === eventId.toString())) {
        throw new Error('User already joined this event');
    }
    
    this.joinedEvents.push({ event: eventId });
    return await this.save();
};

userSchema.methods.getJoinedEvents = async function() {
    await this.populate('joinedEvents.event');
    return this.joinedEvents;
};

userSchema.methods.updateEventStatus = async function(eventId, status) {
    const eventJoined = this.joinedEvents.find(
        je => je.event.toString() === eventId.toString()
    );
    
    if (!eventJoined) {
        throw new Error('Event not found in user\'s joined events');
    }
    
    eventJoined.status = status;
    return await this.save();
};

module.exports = mongoose.model('User', userSchema);