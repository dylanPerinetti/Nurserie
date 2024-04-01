// Vérifiez l'URL pour voir si la variable de requête `error` est présente
if (new URLSearchParams(window.location.search).has('error')) {
  let error = new URLSearchParams(window.location.search).get('error');
  if (error == 'invalidcredentials') {
    document.getElementById('errorMessage').style.display = 'block';
  }
}


