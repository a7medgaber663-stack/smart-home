document.querySelector("form").addEventListener("submit", function(e) {
    const password = document.getElementById("password").value;
    const confirm = document.getElementById("confirm_password").value;
    const errorMsg = document.getElementById("error-message");
  
    if (password !== confirm) {
      e.preventDefault();
      errorMsg.textContent = "كلمة المرور غير متطابقة!";
    }
  });
  