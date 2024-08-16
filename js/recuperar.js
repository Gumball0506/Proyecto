/*
    ----------------------------------------------------
    Comentario Anti-Copyright
    ----------------------------------------------------
    Este trabajo es realizado por:
    - Harold Ortiz Abra Loza
    - William Vega
    - Sergio Vidal
    - Elizabeth Campos
    - Lily Roque
    ----------------------------------------------------
    © 2024 Responsabilidad Social Universitaria. 
    Todos los derechos reservados.
    ----------------------------------------------------
*/

// Aquí puedes incluir tu código JavaScript.

document
  .getElementById("recuperarForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();
    const email = document.getElementById("email").value;

    fetch("/php/recuperar_contrasena.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "email=" + encodeURIComponent(email),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert(
            "Se ha enviado un enlace de recuperación a tu correo. Por favor, revisa tu bandeja de entrada."
          );
        } else {
          alert("Error: " + data.message);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Ha ocurrido un error al procesar tu solicitud.");
      });
  });
