const express   = require('express');
const cors      = require('cors');
const dotenv    = require('dotenv');
const mongoose  = require('mongoose');
const path      = require('path');

const authRoutes  = require('./routes/auth');
const eventRoutes = require('./routes/events');

const dashboardRoutes = require('./routes/dashboard');

dotenv.config({ path: path.join(__dirname, '.env') });

const app = express();

const PORT = process.env.PORT || 5001;

app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

const MONGODB_URI = process.env.MONGODB_URI || 'mongodb://localhost:27017/event-app';
mongoose.connect(MONGODB_URI, {
  useNewUrlParser: true,
  useUnifiedTopology: true,
  serverSelectionTimeoutMS: 5000,
  socketTimeoutMS: 45000
})
.then(() => {
  console.log('Terhubung ke MongoDB');
  app.listen(PORT, () => {
    console.log(`Server berjalan di port ${PORT}`);
  });
})
.catch(err => {
  console.error('Kesalahan koneksi MongoDB:', err);
  process.exit(1);
});

app.use('/api/auth', authRoutes);
app.use('/api/events', eventRoutes);
app.use('/api/dashboard', dashboardRoutes);







