import { createRouter, createWebHistory } from 'vue-router';
import store from '../store/index.js';
import RoomsList from '../components/Frontend/Rooms/RoomsList.vue';
import SearchResults from '../components/Frontend/Rooms/SearchResults.vue';
import RoomPage from '../components/Frontend/Rooms/RoomPage.vue';
import Checkout from '../components/Frontend/Checkout.vue';

// Import the layout
import AppLayout from '../components/Frontend/Layout/AppLayout.vue';
import AdminLayout from '../components/Admin/Layout/AdminLayout.vue'; // Admin Layout

// Import route components
import Login from '../components/Auth/Login.vue';
import Register from '../components/Auth/Register.vue';
import ResetPassword from '../components/Frontend/ResetPassword.vue';
import ForgotPassword from '../components/Frontend/ForgotPassword.vue';
import Profile from '../components/Frontend/Profile.vue';
import Contact from '../components/Frontend/Contact.vue';

import CustomerLayout from '../components/Frontend/CustomerLayout.vue';
import Home from '../components/Frontend/Home.vue';


// Admin Components
import AdminDashboard from '../components/Admin/Dashboard/AdminDashboard.vue';

import BuisnessHours from '../components/Admin/BuisnessHours/BuisnessHours.vue';



import PaymentSetting from '../components/Admin/Settings/PaymentSetting.vue';

import EmailSetting from '../components/Admin/Settings/EmailSetting.vue';
import SmsSetting from '../components/Admin/Settings/SmsSetting.vue';


import GeneralCustomize from '../components/Admin/Customize/GeneralCustomize.vue';


import HomePageHeader from '../components/Admin/Customize/HomePageHeader.vue';

import Customers from '../components/Admin/Customers/Customers.vue';
import AddCustomer from '../components/Admin/Customers/AddCustomer.vue';
import EditCustomer from '../components/Admin/Customers/EditCustomer.vue';

import ContactMessages from '../components/Admin/Customers/ContactMessages.vue';

import Amenities from '../components/Admin/Amenities/Amenities.vue';
import AddAmenity from '../components/Admin/Amenities/AddAmenity.vue';
import EditAmenity from '../components/Admin/Amenities/EditAmenity.vue';

import Rooms from '../components/Admin/Rooms/Rooms.vue';
import AddRoom from '../components/Admin/Rooms/AddRoom.vue';
import EditRoom from '../components/Admin/Rooms/EditRoom.vue';

import ManageLessons from '../components/Admin/Lessons/ManageLessons.vue';

import AddLesson from '../components/Admin/Lessons/AddLesson.vue';
import EditLesson from '../components/Admin/Lessons/EditLesson.vue';

import Bookings from '../components/Admin/Bookings/Bookings.vue';
import BackendBookingDetails from '../components/Admin/Bookings/BookingDetails.vue';

import MyBookings from '../components/Frontend/MyBookings.vue';

import AddReview from '../components/Admin/Review/AddReview.vue';
import ReviewList from '../components/Admin/Review/ReviewList.vue';

import ManagePatientHistories from '../components/Admin/CustomersHistories/ManageHistories.vue';

import AddPatientHistory from '../components/Admin/CustomersHistories/AddPatientHistory.vue';;
import EditPatientHistory from '../components/Admin/CustomersHistories/EditPatientHistory.vue';;



const routes = [
  
  {
    path: '/',
    component: AppLayout,
    children: [
       { path: 'roomsList',
         name: 'roomsList',
         component: RoomsList 
       },
         {
    path: '/search-results',
    name: 'SearchResults',
    component: SearchResults,
  },
       { path: '/room/:roomId',
        name: 'room',
        component: RoomPage,
        meta: { requiresAuth: true },

       },
 
        {
        path: '/checkout/:roomId',
        name: 'checkout',
        component: Checkout,
        meta: { requiresAuth: true },
      },
      {
        name: 'Login',
        path: '/login',
        component: Login,
      },
      {
        name: 'Register',
        path: '/register',
        component: Register,
      },
      {
        name: 'ResetPassword',
        path: '/password/reset/:token',
        component: ResetPassword,
        props: true,
      },
      {
        name: 'ForgotPassword',
        path: '/forgotpassword',
        component: ForgotPassword,
        props: true,
      },
      {
        name: 'Home',
        path: '/',
        component: Home,
      },
     
  
      {
        name: 'Contact',
        path: '/contact',
        component: Contact,
      },
      
      {
        path: '/customerdashboard',
        component: CustomerLayout,
        meta: { requiresAuth: true },
        children: [
          {
            name: 'Profile',
            path: 'profile',
            component: Profile,
          },
              {
            name: 'mybookings',
            path: 'mybookings',
            component: MyBookings,
          },
     
          
              
        ],
      },
    ],
  },
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAdmin: true },
    children: [
      {
        name: 'AdminDashboard',
        path: 'dashboard',
        component: AdminDashboard,
      },
            {
        name: 'Rooms',
        path: 'rooms',
        component: Rooms,
      },
                {
        name: 'Amenities',
        path: 'amenities',
        component: Amenities,
      },
      {
        name: 'BuisnessHours',
        path: 'buisnesshours',
        component: BuisnessHours,
      },
       


       {
        name: 'AddReview',
        path: 'reviews/add',
        component: AddReview,
      },
       {
        name: 'Reviews',
        path: 'reviews',
        component: ReviewList,
      },

{
  path: 'bookings',
  name: 'Bookings',
  component: Bookings, 
},
     {
            name: 'BackendBookingDetails',
            path: 'booking/:id',
            component: BackendBookingDetails,
       },


      {
        name: 'AddRoom',
        path: 'rooms/add',
        component: AddRoom,
      },

          {
        path: 'rooms/edit/:id',
        name: 'EditRoom',
        component: EditRoom,
      },

            {
        name: 'AddAmenity',
        path: 'amenities/add',
        component: AddAmenity,
      },

          {
        path: 'amenities/edit/:id',
        name: 'EditAmenity',
        component: EditAmenity,
      },
     
     
               {
        name: 'Customers',
        path: 'patients',
        component: Customers,
      },
            {
        name: 'AddCustomer',
        path: 'patients/add',
        component: AddCustomer,
      },

          {
        path: 'patients/edit/:id',
        name: 'EditCustomer',
        component: EditCustomer,
      },
      {
  
  path: 'patients/:patientId/histories',    
  name: 'ManageHistories',
  component: ManagePatientHistories, 
},
{
  path: '/patients/:patientId/add-history',
  name: 'AddPatientHistory',
  component:AddPatientHistory,
},
{
      path: 'patients/:patientId/histories/edit/:historyId',
        name: 'EditPatientHistory',
        component: EditPatientHistory,
      },


{
  path: 'contact-messages',
  name: 'ContactMessages',
  component: ContactMessages,
},
      
       {
        name: 'PaymentSetting',
        path: 'paymentsetting',
        component: PaymentSetting,
      },
        {
        name: 'EmailSetting',
        path: 'emailsetting',
        component: EmailSetting,
      },
          {
        name: 'SmsSetting',
        path: 'smssetting',
        component: SmsSetting,
      },
              {
        name: 'GeneralCustomize',
        path: 'generalcustomize',
        component: GeneralCustomize,
      },
      {
        name: 'HomePageHeader',
        path: 'customize/homepageheader',
        component: HomePageHeader,
      },


           
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  
});


router.beforeEach(async (to, from, next) => {
  const isAuthenticated = store.getters['auth/isAuthenticated'];
  const user = store.getters['auth/user']; // Fetch the user from Vuex store
  const authChecked = store.getters['auth/authChecked']; // Get auth check status

  if (!authChecked) {
    // Wait until the auth check is finished
    return next();
  }

  if (to.matched.some(record => record.meta.requiresAuth) && !isAuthenticated) {
    next({ path: '/login', query: { redirect: to.fullPath } });
  } else if (to.matched.some(record => record.meta.requiresAdmin)) {
    if (!user || user.role !== 'admin') {
      next({ path: '/' });
    } else {
      next();
    }
  } else {
    next();
  }
});




export default router;

