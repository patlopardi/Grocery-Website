function validatePsw() {
  var psw1 = document.getElementById("password").value;
  var psw2 = document.getElementById("password-confirm").value;
  console.log(psw1);

  if (psw1 == psw2) {
  } else {
    alert("Passwords do not match.");
    document.getElementById("password").value = "";
    document.getElementById("password-confirm").value = "";
  }
}

function goTo(page) {
  window.location.href = page;
}
