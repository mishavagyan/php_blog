let captcha = {}
let pathname = window.location.pathname;
let filename = pathname.split('/').pop();

function validatePassword(password) {
    // const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>])[A-Za-z\d!@#$%^&*(),.?":{}|<>]{8,}$/;
    // return passwordRegex.test(password);
    return true;
}

// function validateEmail(email) {
//     const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
//     return emailRegex.test(email);
// }
  
function validateCaptcha(key, res) {
    return res == captcha[key]
}

function login() {
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const loginCaptcha = document.getElementById("loginCaptcha").value;
    
    if(validatePassword(password)) {
        if(validateCaptcha('login', loginCaptcha)) {
            return true;
        } else {
            alert("Incorrect captcha");
            InitCaptcha('login');
            return false;
        }
    } else {
        alert("Incorrect password");
        return false;
    }
}


function register() {
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const regCaptcha = document.getElementById("registerCaptcha").value;
    if(validateCaptcha('register', regCaptcha)) {
        if (validatePassword(password)) {
            return true;
        } else {
            alert("Password must include at least 1 lowercase letter, 1 uppercase letter, 1 number, 1 special character, and be at least 8 characters long");
            return false;
        }
    } else {
        alert("Invalid Captcha");
        return false;
    }
}

function change() {
    const password = document.getElementById("password").value;
    const captcha = document.getElementById("changeCaptcha").value;
    if(validateCaptcha('change', captcha)) {
        if (validatePassword(password)) {
            return true;
        } else {
            alert("Password must include at least 1 lowercase letter, 1 uppercase letter, 1 number, 1 special character, and be at least 8 characters long");
            return false;
        }
    } else {
        alert("Invalid Captcha");
        return false;
    }
}


function InitCaptcha(key) {
    let num1 = Math.floor(Math.random() * 10);
    let num2 = Math.floor(Math.random() * 10);
    captcha[key] = num1 + num2;

    if (document.getElementById(`${key}Num1`)) {
        document.getElementById(`${key}Num1`).innerText = num1;
    }

    if (document.getElementById(`${key}Num2`)) {
        document.getElementById(`${key}Num2`).innerText = num2;
    }
}

if(filename == "login.php") {
    InitCaptcha("login");

    document.getElementById("loginForm").addEventListener('submit', (e) => {
        e.preventDefault();
        // document.getElementById("loginUsername").value = "";
        let b = login();
        if(b) {
            document.getElementById("loginForm").submit();
        }
        InitCaptcha('login');
        document.getElementById("password").value = "";
        document.getElementById("loginCaptcha").value = "";
    });
    
} else if(filename == "register.php" || filename == "forgot.php") {
    InitCaptcha("register");

    document.getElementById("registrationForm").addEventListener('submit', (e) => {
        e.preventDefault();
        let b = register();
        InitCaptcha('register');
        if(b) {
            document.getElementById("registrationForm").submit();
        }
        document.getElementById("password").value = "";
        document.getElementById("registerCaptcha").value = "";
    });
}


if(filename == "changePassword.php") {
    InitCaptcha("change");

    // document.getElementById("changeForm").addEventListener('submit', (e) => {
    //     e.preventDefault();
    //     let b = change();
    //     InitCaptcha();
    //     if(b) {
    //         document.getElementById("changeForm").submit();
    //     }
    //     document.getElementById("password").value = "";
    //     document.getElementById("changeCaptcha").value = "";
    // });
}