<style>
    /* Login Popup */


.login-popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(10, 10, 10, 0.6);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.login-popup {
    width: 80%;
    max-width: 800px;
    background: #fff;
    display: flex;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    padding: 0.5rem;
}


.popup-left {
    flex: 1;
    border-radius: 1rem;
    overflow: hidden;
}

.popup-left img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.btn-file {
    margin: 0;
    padding: 0;
    position: relative;
    z-index: 1;
}

.btn-file__actions {
    margin: 0;
    padding: 0;
}

.btn-file__actions__item {
    padding: 1.7rem 1.5rem;
    font-size: 1.5rem;
    color: #a0adbf;
    cursor: pointer;
    text-decoration: none;
    border: 1px solid #a0adbf;
    border-radius: 1rem;
}

.btn-file__actions__item:hover,
.btn-file__actions__item:focus {
    color: #636b6f;
    background-color: rgba(211, 224, 233, 0.1);
}

.btn-file__actions__item:hover--shadow,
.btn-file__actions__item:focus--shadow {
    box-shadow: #d3e0e9 0 0 60px 15px;
}

.btn-file__actions__item--shadow {
    display: inline-block;
    position: relative;
    z-index: 1;
}

.btn-file__actions__item--shadow::before {
    content: " ";
    box-shadow: #fff 0 0 60px 40px;
    display: inline-block;
    position: absolute;
    top: 50%;
    left: 0;
    width: 100%;
    z-index: -1;
}

.btn-file__preview {
    opacity: 0.5;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    position: absolute;
    z-index: -1;
    border-radius: 35px;
    background-size: cover;
    background-position: center;
}

.form-group label.attachment {
    width: 100%;
}

.form-group label.attachment .btn-create>a,
.form-group label.attachment .btn-create>div {
    margin-top: 5px;
}

.form-group label.attachment input[type="file"] {
    display: none;
}

.popup-right {
    flex: 1;
    padding: 30px;
    position: relative;
    overflow: hidden;
}

.close-btn {
    position: absolute;
    top: 0rem;
    right: 0;
    font-size: 22px;
    background: #ff4d4d;
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    cursor: pointer;
}

/* === TABS === */
.tab-buttons {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    border-radius: 4rem;
    background-color: #002b5b1a;
    padding: 0.7rem;
}

.tab-btn {
    flex: 1;
    padding: 10px 0;
    border: none;
    cursor: pointer;
    font-weight: bold;
    border-radius: 2rem;
    background: transparent;
}

.tab-btn.active {
    background: #002f6c;
    color: #fff;
}

/* === FORM SLIDER CONTAINER === */
.xfw-form-wrapper {
    display: flex;
    width: 200%;
    /* Two forms side by side */
    transition: transform 0.5s ease-in-out;
}

.xfw-form-wrapper-register {
    display: flex;
    width: 100%;
    /* Two forms side by side */
}

.xfw-form-wrapper-container {
    overflow-x: hidden;
}

/* === FORM PANELS === */
.xfw-form-panel {
    width: 100%;
    /* Each panel takes full width of .popup-right */
    flex-shrink: 1;
    box-sizing: border-box;
    padding: 0 1rem;
}

.popup-right .xfw-form-wrapper-container p.title {
    font-size: 2rem;
    color: var(--primary-clr);
    font-weight: 600;
    margin-bottom: 2rem;
}


/* === SLIDE TO STAFF FORM === */
.popup-right.xfw-staff-active .xfw-form-wrapper {
    transform: translateX(-50%);
}

/* === FORM ELEMENTS === */
.xfw-form-panel label {
    display: block;
    font-weight: 600;
    margin: 10px 0 5px;
    font-size: 1.5rem;
}

.xfw-form-panel input[type="text"],
.xfw-form-panel input[type="email"],
.xfw-form-panel input[type="password"] {
    width: 100%;
    padding: 1.8rem 1.5rem;
    margin-top: .5rem;
    border: 1px solid #ddd;
    border-radius: 6px;
}

/* === OTP === */
.otp-container {
    margin-top: 20px;
}

.otp-container small {
    font-size: 1rem;
}

.otp-inputs {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}

.otp-inputs input {
    width: 40px;
    height: 40px;
    text-align: center;
    font-size: 18px;
    border-radius: 6px;
    border: 1px solid #ccc;
}

/* === SUBMIT BUTTON === */
.btn-login-submit {
    width: 100%;
    padding: 12px;
    background-color: #002f6c;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    margin-top: 15px;
    cursor: pointer;
}

.login-popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(10, 10, 10, 0.6);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.login-popup {
    width: 80%;
    max-width: 800px;
    background: #fff;
    display: flex;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    padding: 0.5rem;
}

.popup-left {
    flex: 1;
    border-radius: 1rem;
    overflow: hidden;
}

.popup-left img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.popup-right {
    flex: 1;
    padding: 30px;
    position: relative;
}

.close-btn {
    position: absolute;
    top: 0rem;
    right: 0;
    font-size: 22px;
    background: #ff4d4d;
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    cursor: pointer;
}

.tab-buttons {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    border-radius: 4rem;
    background-color: #002B5B1A;
    padding: 0.7rem;
}

.tab-btn {
    flex: 1;
    padding: 10px 0;
    border: none;
    cursor: pointer;
    font-weight: bold;
    border-radius: 2rem;
}

.tab-btn.active {
    background: #002f6c;
    color: #fff;
}

#loginForm label {
    display: block;
    font-weight: 600;
    margin: 10px 0 5px;
    font-size: 1.5rem;
}

#loginForm input[type="text"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
}

.otp-container {
    margin-top: 20px;
}

.otp-container small {
    font-size: 1rem;
}

.otp-inputs {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}

.otp-inputs input {
    width: 40px;
    height: 40px;
    text-align: center;
    font-size: 18px;
    border-radius: 6px;
    border: 1px solid #ccc;
}

.btn-login-submit {
    width: 100%;
    text-align: center;
    display: block;
    padding: 12px;
    background-color: #002f6c;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    margin-top: 15px;
    cursor: pointer;
}

</style> <!-- All Popup -->

 <!-- Registration Status Check -->
 <div class="login-popup-overlay" id="registerPopup">
     <div class="login-popup">
         <div class="popup-left">
             <img src="../public/reg1.png" alt="Login Visual" />
         </div>
         <div class="popup-right">
             <button class="close-btn" id="rclosePopup">&times;</button>

             <div class="xfw-form-wrapper-container center-line">
                 <p class="title">Registration Status Check</p>
                 <div class="xfw-form-wrapper-register">
                     <div class="xfw-form-panel xfw-form-panel-2 xfw-student-form xfw-active-form">
                         <form id="studentLoginForm">
                             <label for="loginInput">Mobile Number</label>
                             <input type="text" id="loginInput" placeholder="Enter Mobile Number" maxlength="10" required />

                             <label for="loginInput">Email Address</label>
                             <input type="text" id="loginInput" placeholder="Enter Email Address" maxlength="10" required />


                             <button class="btn-login-submit" type="submit">
                                 Login
                             </button>
                             <p class="register-link">
                                 Don’t have an account? <a href="#">Register Here</a>
                             </p>
                         </form>
                     </div>

                 </div>
             </div>
         </div>
     </div>
 </div>

 <!-- Registration Status Check -->
 <div class="login-popup-overlay" id="loginPopup">
     <div class="login-popup">
         <div class="popup-left">
             <img src="../public/reg1.png" alt="Login Visual" />
         </div>
         <div class="popup-right">
             <button class="close-btn" id="closePopup">&times;</button>
             <div class="tab-buttons">
                 <button class="tab-btn active" data-tab="student">
                     Student Login
                 </button>
                 <button class="tab-btn" data-tab="staff">Staff Login</button>
             </div>
             <div class="xfw-form-wrapper-container">
                 <div class="xfw-form-wrapper">
                     <div class="xfw-form-panel xfw-student-form xfw-active-form">
                         <form id="studentLoginForm">
                             <label for="loginInput">Mobile Number</label>
                             <input type="text" id="loginInput" placeholder="Enter Mobile Number" maxlength="10" />

                             <div class="otp-container">
                                 <label>OTP</label>
                                 <div class="otp-inputs">
                                     <input type="text" maxlength="1" />
                                     <input type="text" maxlength="1" />
                                     <input type="text" maxlength="1" />
                                     <input type="text" maxlength="1" />
                                     <input type="text" maxlength="1" />
                                     <input type="text" maxlength="1" />
                                 </div>
                                 <small>Didn't receive the OTP?
                                     <a href="#">Resend OTP</a></small>
                             </div>

                             <!-- <button class="btn-login-submit" type="submit"> Login </button> -->
                             <a href="../Student Dashboard/index.html" class="btn-login-submit" type="submit">
                                 Login
                             </a>
                             <p class="register-link">
                                 Don’t have an account? <a href="#">Register Here</a>
                             </p>
                         </form>
                     </div>

                     <div class="xfw-form-panel xfw-staff-form">
                         <form id="staffLoginForm">
                             <label for="staffEmail">Email</label>
                             <input type="email" id="staffEmail" placeholder="Enter Email" />

                             <label for="staffPassword">Password</label>
                             <input type="password" id="staffPassword" placeholder="Enter Password" />

                             <!-- <button class="btn-login-submit" type="submit"> Login </button> -->
                             <a href="../Admin Dashboard/index.html" class="btn-login-submit" type="submit">
                                 Login
                             </a>
                             <p class="register-link">
                                 Forgot password? <a href="index.html">Reset Here</a>
                             </p>
                         </form>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>

 <!-- footer section  -->


 <section class="footer-section">
     <p>Copyright © 2025 RNTU University. All rights reserved.</p>
     <img src="{{ asset('frontend/img/footer-img.png') }}" class="footer-img" alt="">
 </section>

 <!-- Bootstrap v5.3.7 -->
 <script src="{{ asset('frontend/js/Boostrap/bootstrap.bundle.js') }}"></script>
 {{-- <script src="{{ asset('frontend/js/jquery3-6-0.min.js') }}"></script> --}}
 <script src="{{ asset('frontend/js/jquery3-7-1min.js') }}"></script>
 <script src="{{ asset('frontend/js/style.js') }}"></script>
 <script src="{{ asset('frontend/js/sweetalert.js') }}"></script>




 <script>
     // JavaScript for tab functionality (can be merged with register.js)
     document.addEventListener("DOMContentLoaded", () => {
        
         // Login Popup functionality (existing from your original HTML)
         const loginBtn = document.getElementById("btn-login");
         const loginPopup = document.getElementById("loginPopup");
         const closePopupBtn = document.getElementById("closePopup");
         const tabBtns = document.querySelectorAll(".tab-btn");
         const formPanels = document.querySelectorAll(".xfw-form-panel");

         loginBtn.addEventListener("click", () => {
             loginPopup.style.display = "flex";
         });

         closePopupBtn.addEventListener("click", () => {
             loginPopup.style.display = "none";
         });

         tabBtns.forEach((btn) => {
             btn.addEventListener("click", () => {
                 tabBtns.forEach((b) => b.classList.remove("active"));
                 btn.classList.add("active");

                 const targetTab = btn.dataset.tab;

                 formPanels.forEach((panel) => {
                     panel.classList.remove("xfw-active-form");
                 });

                 if (targetTab === "student") {
                     document
                         .querySelector(".xfw-student-form")
                         .classList.add("xfw-active-form");
                 } else if (targetTab === "staff") {
                     document
                         .querySelector(".xfw-staff-form")
                         .classList.add("xfw-active-form");
                 }
                 // Logic to slide the form wrapper container
                 const formWrapper = document.querySelector(".xfw-form-wrapper");
                 if (targetTab === "staff") {
                     formWrapper.style.transform = "translateX(-50%)";
                 } else {
                     formWrapper.style.transform = "translateX(0)";
                 }
             });
         });
     });
 </script>

 <script>
     // Login Popup functionality (existing from your original HTML)
     const loginBtn = document.getElementById("btn-regitser");
     const loginPopup = document.getElementById("registerPopup");
     const closePopupBtn = document.getElementById("rclosePopup");
     const tabBtns = document.querySelectorAll(".tab-btn");
     const formPanels = document.querySelectorAll(".xfw-form-panel");

     loginBtn.addEventListener("click", () => {
         loginPopup.style.display = "flex";
     });

     closePopupBtn.addEventListener("click", () => {
         loginPopup.style.display = "none";
     });

     tabBtns.forEach((btn) => {
         btn.addEventListener("click", () => {
             tabBtns.forEach((b) => b.classList.remove("active"));
             btn.classList.add("active");

             const targetTab = btn.dataset.tab;

             formPanels.forEach((panel) => {
                 panel.classList.remove("xfw-active-form");
             });

             if (targetTab === "student") {
                 document
                     .querySelector(".xfw-student-form")
                     .classList.add("xfw-active-form");
             } else if (targetTab === "staff") {
                 document
                     .querySelector(".xfw-staff-form")
                     .classList.add("xfw-active-form");
             }
             // Logic to slide the form wrapper container
             const formWrapper = document.querySelector(".xfw-form-wrapper");
             if (targetTab === "staff") {
                 formWrapper.style.transform = "translateX(-50%)";
             } else {
                 formWrapper.style.transform = "translateX(0)";
             }
         });
     });
 </script>

 </body>

 </html>