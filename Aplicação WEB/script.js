// ===== Validação do formulário de PAÍSES =====
function validateCountryForm(form) {
  // Captura e remove espaços em branco dos campos do formulário
  const nome = form.nome.value.trim();
  const continente = form.continente.value.trim();
  const populacao = parseInt(form.populacao.value, 10);
  const idioma = form.idioma.value.trim();

  // Verifica se todos os campos obrigatórios estão preenchidos e válidos
  if (!nome || !continente || !idioma || !populacao || populacao <= 0) {
    // Caso falte algum dado ou a população seja inválida, exibe alerta e impede o envio
    alert('Preencha todos os campos do país corretamente.');
    return false;
  }

  // Se passou por todas as validações, o formulário pode ser enviado
  return true;
}

// ===== Validação do formulário de CIDADES =====
function validateCityForm(form) {
  // Captura os valores preenchidos no formulário
  const nome = form.nome.value.trim();
  const populacao = parseInt(form.populacao.value, 10);
  const id_pais = form.id_pais.value;

  // Confere se todos os campos possuem dados válidos
  if (!nome || !id_pais || !populacao || populacao <= 0) {
    alert('Preencha todos os campos da cidade corretamente.');
    return false;
  }

  // Se estiver tudo certo, o envio do formulário é permitido
  return true;
}

// ===== Confirmação antes de excluir um país =====
function confirmDeleteCountry(evt, id) {
  // Exibe uma caixa de confirmação para o usuário.
  // Explica que a exclusão não será possível se houver cidades ligadas ao país.
  return confirm('Tem certeza que deseja excluir este país? Se houver cidades vinculadas a ele, a exclusão não será permitida.');
}
