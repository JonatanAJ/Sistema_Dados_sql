function showForm(formId) {
    const forms = document.querySelectorAll('.tab-pane');
    forms.forEach(form => {
        form.classList.remove('active');
    });
    document.getElementById(formId).classList.add('active');
}

function toggleSubButtons() {
    const mainButton = document.getElementById('mainButton');
    const buttonContainer = mainButton.closest('.button-container');
    
    // Alterna a exibição dos sub-botões
    const isShowing = buttonContainer.classList.toggle('show-sub-buttons');
    
    // Se os sub-botões forem exibidos, mostre automaticamente a aba de Parcelas
    if (isShowing) {
        showForm('parcelas');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    showForm('identificacao');
});
