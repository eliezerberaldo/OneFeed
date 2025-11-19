let trilho = document.getElementById("trilho"); 
let main = document.querySelector("main"); 

trilho.addEventListener("click", () => { 
    trilho.classList.toggle("dark"); 
    main.classList.toggle("dark");
});

document.addEventListener('DOMContentLoaded', function() {
        
    const bellButton = document.getElementById('notification-bell');
    const dropdown = document.getElementById('notification-dropdown');
    const badge = document.getElementById('notification-badge');
    const clearBtn = document.getElementById('clear-notifications-btn');
    const notificationList = document.getElementById('notification-list');
        
    const friendBell = document.getElementById('friend-request-bell');
    const friendDropdown = document.getElementById('friend-request-dropdown');
    const friendBadge = document.getElementById('friend-request-badge');
    const friendRequestList = document.getElementById('friend-request-list');
    const searchInput = document.getElementById('friend-search-input');
    const searchResults = document.getElementById('friend-search-results');
    const friendList = document.getElementById('friend-list');

     bellButton.addEventListener('click', function(event) {
        event.stopPropagation();
        friendDropdown.style.display = 'none';
        
        const isHidden = dropdown.style.display === 'none';
        dropdown.style.display = isHidden ? 'block' : 'none';
        if (isHidden && badge) {
            fetch('../app/processing/marcar_lidas.php', { method: 'POST' })
            .then(response => response.json())
            .then(data => {
               if (data.success) {
                    badge.style.display = 'none';
                    dropdown.querySelectorAll('.notification-item.unread').forEach(item => {
                        item.classList.remove('unread');
                    });
                }
            })
            .catch(error => console.error('Erro ao marcar notificações como lidas:', error));
        }
    });

    clearBtn.addEventListener('click', function(event) {
        event.stopPropagation();
        if (!confirm('Tem certeza que deseja limpar TODAS as notificações?')) return;

        fetch('../app/processing/limpar_notificacoes.php', { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                notificationList.innerHTML = '<div class="notification-item-empty">Nenhuma notificação nova.</div>';
                if (badge) badge.style.display = 'none';
            } else {
                alert('Erro ao limpar as notificações.');
            }
        })
        .catch(error => console.error('Erro ao limpar notificações:', error));
    });

    friendBell.addEventListener('click', function(event) {
        event.stopPropagation();
        dropdown.style.display = 'none';
            
        const isHidden = friendDropdown.style.display === 'none';
        friendDropdown.style.display = isHidden ? 'block' : 'none';
    });

    friendRequestList.addEventListener('click', function(event) {
        const target = event.target;
            
        if (target.tagName === 'BUTTON' && (target.classList.contains('btn-accept') || target.classList.contains('btn-reject'))) {
                
            const item = target.closest('.friend-request-item');
            const solicitacaoId = item.dataset.id;
            const acao = target.dataset.acao; 
                
            item.querySelectorAll('button').forEach(btn => btn.disabled = true);

            const formData = new FormData();
            formData.append('solicitacao_id', solicitacaoId);
            formData.append('acao', acao);

            fetch('../app/processing/responder_solicitacao.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.sucesso) {
                    item.style.opacity = 0;
                    setTimeout(() => { 
                        item.remove(); 
                        if (acao === 'aceitar') {
                            alert('Amigo adicionado! A página será recarregada.');
                            window.location.reload();
                        }
                    }, 300);
                        
                    if (friendBadge) {
                        const contagem = parseInt(friendBadge.textContent) - 1;
                        if (contagem > 0) {
                            friendBadge.textContent = contagem;
                        } else {
                            friendBadge.style.display = 'none';
                        }
                    }
                } else {
                    alert('Erro: ' + data.mensagem);
                    item.querySelectorAll('button').forEach(btn => btn.disabled = false);
                }
            });
        }
    });

    let searchTimeout;
    searchInput.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        const termo = searchInput.value.trim();

        if (termo.length < 2) {
            searchResults.style.display = 'none';
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`../app/processing/buscar_usuarios.php?termo=${encodeURIComponent(termo)}`)
            .then(response => response.json())
            .then(data => {
                if (data.sucesso && data.usuarios.length > 0) {
                    searchResults.innerHTML = '';
                    data.usuarios.forEach(user => {
                        const userEl = document.createElement('div');
                        userEl.classList.add('search-result-item');
                        userEl.textContent = user.nome;
                        userEl.dataset.id = user.id;
                        searchResults.appendChild(userEl);
                    });
                    searchResults.style.display = 'block';
                } else {
                    searchResults.style.display = 'none';
                }
            });
        }, 300);
    });

    searchResults.addEventListener('click', function(event) {
        const target = event.target;
        if (target.classList.contains('search-result-item')) {
            const receptorId = target.dataset.id;
            const nome = target.textContent;

            if (!confirm(`Deseja enviar um pedido de amizade para ${nome}?`)) {
                return;
            }

            const formData = new FormData();
            formData.append('receptor_id', receptorId);

            fetch('../app/processing/enviar_solicitacao.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.mensagem); 
                searchInput.value = '';
                searchResults.style.display = 'none';
            });
        }
    });

    friendList.addEventListener('click', function(event) {
        const target = event.target;

        if (target.classList.contains('remove-friend-btn')) {
                
            const item = target.closest('.friend-item');
            const amigoId = item.dataset.id;
            const nome = item.querySelector('span').textContent;

            if (!confirm(`Tem certeza que deseja remover ${nome} da sua lista de amigos?`)) {
                return;
            }

            const formData = new FormData();
            formData.append('amigo_id', amigoId);

            fetch('../app/processing/remover_amizade.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.sucesso) {
                    item.remove();
                        
                    if (friendList.children.length === 0) {
                        friendList.innerHTML = '<p class="friend-list-empty">Adicione amigos para vê-los aqui.</p>';
                    }
                } else {
                    alert('Erro ao remover amigo: ' + data.mensagem);
                }
            })
            .catch(error => {
                console.error('Erro ao remover amizade:', error);
                alert('Ocorreu um erro de rede.');
            });
        }
    });
        
    document.addEventListener('click', function(event) {
        if (dropdown.style.display === 'block' && !dropdown.contains(event.target) && !bellButton.contains(event.target)) {
            dropdown.style.display = 'none';
        }
            
        if (friendDropdown.style.display === 'block' && !friendDropdown.contains(event.target) && !friendBell.contains(event.target)) {
            friendDropdown.style.display = 'none';
        }

        if (searchResults.style.display === 'block' && !searchResults.contains(event.target) && !searchInput.contains(event.target)) {
            searchResults.style.display = 'none';
        }
    });
});