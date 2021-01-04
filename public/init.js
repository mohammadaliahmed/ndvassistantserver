const firebaseConfig = {
  apiKey: "AIzaSyD2E1rfj5irI_BmZTACEuv1KR9Amyql_aU",
  authDomain: "ndvassistant.firebaseapp.com",
  projectId: "ndvassistant",
  storageBucket: "ndvassistant.appspot.com",
  messagingSenderId: "355087823038",
  appId: "1:355087823038:web:dea3ffd052adb6ad126eef",
  measurementId: "G-BRTBKGRDH1"
};
  firebase.initializeApp(firebaseConfig);


const messaging=firebase.messaging();
messaging.requestPermission()
.then(function(token){
    console.log('have permission');
    return messaging.getToken();
})
.then(function(token){
    console.log(token)
})
.catch(function(error){
    console.log('error here');
})

messaging.onMessage(function(payload){
    console.log('onMessage',payload);
    const notificationTitle = 'dfsdf';
    const notificationOptions = {
        body: 'sfBackground Message body.',
        icon: '/firebase-logo.png'
    };

    self.registration.showNotification(notificationTitle,
        notificationOptions);
   
})