function validateCountryForm(form) {
  const nome = form.nome.value.trim();
  const continente = form.continente.value.trim();
  const populacao = parseInt(form.populacao.value, 10);
  const idioma = form.idioma.value.trim();
  if (!nome || !continente || !idioma || !populacao || populacao <= 0) {
    alert('Preencha todos os campos do país corretamente.');
    return false;
  }
  return true;
}

function validateCityForm(form) {
  const nome = form.nome.value.trim();
  const populacao = parseInt(form.populacao.value, 10);
  const id_pais = form.id_pais.value;
  if (!nome || !id_pais || !populacao || populacao <= 0) {
    alert('Preencha todos os campos da cidade corretamente.');
    return false;
  }
  return true;
}

function confirmDeleteCountry(evt, id) {
  return confirm('Tem certeza que deseja excluir este país? Se houver cidades vinculadas a ele, a exclusão não será permitida.');
}
