const pass = document.getElementById("Contraseña"),
      icon = document.querySelector(".show-password");

icon.addEventListener("click", e => {
    if (pass.type === "password") {
        pass.type = "text";
    } else {
        pass.type = "password";
    }
});
