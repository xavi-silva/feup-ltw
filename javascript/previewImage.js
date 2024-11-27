function previewImages() {
    const previewContainer = document.getElementById('imagePreview');
    const fileInput = document.getElementById('imagesInp');
    const files = fileInput.files;

    previewContainer.innerHTML = ''; // Limpar a pré-visualização atual

    // Loop através de todos os arquivos selecionados
    for (let i = 0; i < files.length; i++) {
        const file = files[i];

        // Verificar se o arquivo é uma imagem
        if (!file.type.startsWith('image/')) {
            console.log('O arquivo selecionado não é uma imagem: ', file.name);
            continue;
        }

        // Criar elemento de imagem para exibir a pré-visualização
        const imgElement = document.createElement('img');
        imgElement.classList.add('preview-image');
        imgElement.style.maxWidth = '100px'; // Definir largura máxima da imagem na pré-visualização

        // Configurar uma função de leitura para carregar a imagem como URL de dados
        const reader = new FileReader();
        reader.onload = (event) => {
            imgElement.src = event.target.result; // Definir a origem da imagem como URL de dados
        };

        // Ler o arquivo como URL de dados
        reader.readAsDataURL(file);

        // Adicionar a imagem à pré-visualização
        previewContainer.appendChild(imgElement);
    }
}

// Adicionar um ouvinte de evento de mudança ao input de arquivos
document.getElementById('imagesInp').addEventListener('change', previewImages);
