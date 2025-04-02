import { createStore } from 'vuex'; // Updated import
import auth from './modules/auth.js';
import keys from './modules/keys.js';
import paymentSetting from './modules/backend/paymentSetting.js';
import backendHomePageHeader from './modules/backend/HomePageHeader.js';
import backendUsers from './modules/backend/users.js';
import backendGeneralCustomize from './modules/backend/generalCustomize.js';
import generalCustomize from './modules/generalcustomize.js';
import contact from './modules/contact.js';
import backendContact from './modules/backend/contact.js';
import backendServices from './modules/backend/services.js';
import backendLessons from './modules/backend/lessons.js';
import backendBookings from './modules/backend/bookings.js';
import booking from './modules/booking.js';
import reviews from './modules/review.js';
import backendReview from './modules/backend/review.js';
import buisnessHours from './modules/backend/buisnessHours.js';
import rooms from './modules/rooms.js';
import backendCustomers from './modules/backend/customers.js';
import backendPatientHistories from './modules/backend/patienthistories.js';
import emailSetting from './modules/backend/emailSetting.js';
import smsSetting from './modules/backend/smsSetting.js';





const store = createStore({
  modules: {
    backendServices,  // Corrected module name
    rooms,
    auth,
    paymentSetting,
    emailSetting,
    smsSetting,
    keys,
    backendHomePageHeader,
    backendUsers,
    backendGeneralCustomize,
    generalCustomize,
    contact,
    backendContact,
    backendLessons,
    backendBookings,
    booking,
    reviews,
    backendReview,
    buisnessHours,
    backendCustomers,
    backendPatientHistories,
  },

});


export default store;

