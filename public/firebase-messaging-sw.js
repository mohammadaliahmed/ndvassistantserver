// Import and configure the Firebase SDK
// These scripts are made available when the app is served or deployed on Firebase Hosting
// If you do not serve/host your project using Firebase Hosting see https://firebase.google.com/docs/web/setup
// importScripts('https://www.gstatic.com/firebasejs/8.0.1/firebase-app.js');
// importScripts('https://www.gstatic.com/firebasejs/8.0.1/firebase-messaging.js');


 var firebaseConfig = {
    apiKey: "AIzaSyD2E1rfj5irI_BmZTACEuv1KR9Amyql_aU",
    authDomain: "ndvassistant.firebaseapp.com",
    projectId: "ndvassistant",
    storageBucket: "ndvassistant.appspot.com",
    messagingSenderId: "355087823038",
    appId: "1:355087823038:web:dea3ffd052adb6ad126eef",
    measurementId: "G-BRTBKGRDH1"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  firebase.analytics();
  // Initialize Firebase
const messaging = firebase.messaging();


messaging.setBackgroundMessageHandler(function(payload){
    const title=payload.title;
    const options={
        body:payload.data.status
    };
    return self.registration.showNotification(title,options);
});
messaging.onBackgroundMessage(function(payload) {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
    // Customize notification here
    const notificationTitle = 'Background sdf Message Title';
    const notificationOptions = {
        body: 'Background Message dsfbody.',
        icon: '/firebase-logo.png'
    };

    self.registration.showNotification(notificationTitle,
        notificationOptions);
});