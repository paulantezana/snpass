let Report = {
    init(){
        RequestApi.fetch('/report/summary').then(res => {
            if (res.success){
                this.processData(res.result);
            } else {
                SnModal.error({ title: 'Algo salió mal', content: res.message })
            }
        }).catch(err => {
            SnModal.error({ title: 'Algo salió mal', content: err.message })
        })
    },
    processData(data){
        let userCopy = document.getElementById('userCopy');
        if (userCopy){
            let userCopyHtml = '';
            [...data.userCopy].forEach(item => {
                userCopyHtml += `
                            <div class="avatarList-item">
                                <div class="SnAvatar" data-tooltip="${item.user_name}">
                                    <span class="SnAvatar-counter">${item.count}</span>
                                </div>
                                <div class="avatarList-name">${item.user_name}</div>
                            </div>`;
            });
            userCopy.innerHTML = userCopyHtml;
        }

        let customerCopy = document.getElementById('customerCopy');
        if (customerCopy){
            let customerCopyHtml = '';
            [...data.customerCopy].forEach(item => {
                customerCopyHtml += `
                            <div class="avatarList-item">
                                <div class="SnAvatar" data-tooltip="${item.name}">
                                    <span class="SnAvatar-counter">${item.count}</span>
                                </div>
                                <div class="avatarList-name">${item.name}</div>
                            </div>`;
            });
            customerCopy.innerHTML = customerCopyHtml;
        }

        let userCount = document.getElementById('userCount');
        if (userCount){
            userCount.textContent = data.total.user;
        }

        let customerCount = document.getElementById('customerCount');
        if (customerCount){
            customerCount.textContent = data.total.customer;
        }

        let sessionCount = document.getElementById('sessionCount');
        if (sessionCount){
            sessionCount.textContent = data.total.session;
        }
    }
};

Report.init();