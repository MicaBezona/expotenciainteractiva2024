document.addEventListener('DOMContentLoaded', function() {
    updateVisitCount();
    loadTopProjects();
    loadVotes();

    document.getElementById('uploadForm').addEventListener('submit', function(event) {
        event.preventDefault();
        uploadProject();
    });
});

function showSection(section) {
    // Ocultar todas las secciones
    document.querySelectorAll('main > section').forEach(function(sec) {
        sec.style.display = 'none';
    });

    // Mostrar la sección seleccionada
    document.getElementById(section).style.display = 'block';
}

function updateVisitCount() {
    $.ajax({
        url: 'server.php',
        method: 'GET',
        data: { action: 'get_visit_count' },
        success: function(response) {
            $('#visit_count').text(response);
        }
    });
}

function loadTopProjects() {
    $.ajax({
        url: 'server.php',
        method: 'GET',
        data: { action: 'get_top_projects' },
        success: function(response) {
            $('#top_projects').html(response);
        }
    });
}

function loadVotes() {
    $.ajax({
        url: 'server.php',
        method: 'GET',
        data: { action: 'get_votes' },
        success: function(response) {
            $('#lista_votacion').html(response);
        }
    });
}

function uploadProject() {
    var formData = new FormData(document.getElementById('uploadForm'));
    formData.append('action', 'upload_project');

    $.ajax({
        url: 'server.php',
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            alert(response);
            document.getElementById('uploadForm').reset();
        }
    });
}

function vote(projectId) {
    $.ajax({
        url: 'server.php',
        method: 'POST',
        data: { action: 'vote', project_id: projectId },
        success: function(response) {
            alert(response);
            loadVotes();
            loadTopProjects();
        }
    });
}
