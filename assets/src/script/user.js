let  UserForm = {
    currentModeForm : 'create',
    modalName : 'userModalForm',

    currentForm : document.getElementById('userForm'),
    submitButton : document.getElementById('userFormSubmit'),

    loading : false,
    init() {
        this.list();
    },
    list(){

    },
    setLoading(state){
        this.loading = state;
        if (this.loading){
            if(this.submitButton){
                this.submitButton.setAttribute('disabled','disabled');
                this.submitButton.classList.add('loading');
            }
        } else {
            if(this.submitButton){
                this.submitButton.removeAttribute('disabled');
                this.submitButton.classList.remove('loading');
            }
        }
    },

    clearForm(){
        if (this.currentForm){
            this.currentForm.reset();
        }
    },

    submit(event){
        event.preventDefault();

        let url = '';
        let userSendData = {};
        userSendData.password =  document.getElementById('userPassword').value || '';
        userSendData.email =  document.getElementById('userEmail').value || '';
        userSendData.userName =  document.getElementById('userUserName').value || '';
        userSendData.state =  document.getElementById('userState').checked || false;
        userSendData.userRoleId =  document.getElementById('userUserRoleId').value || '';

        if (this.currentModeForm === 'create'){
            url = '/api/user/create';
        }
        if (this.currentModeForm === 'update'){
            url = '/api/user/update';
            userSendData.userId = document.getElementById('userId').value || 0;
        }
        if (this.currentModeForm === 'updatePassword'){
            url = '/api/user/updatePassword';
            userSendData = {
                password :  document.getElementById('userPassword').value || '',
                userId : document.getElementById('userId').value || 0,
            }
        }

        RequestApi.fetch(url,{
            method: 'POST',
            body: userSendData
        }).then(res => {
            if (res.success){
                SnModal.close(this.modalName);
                SnMessage.success({ content: res.message });
                location.reload();
            } else {
                SnModal.error({ title: 'Algo salió mal', content: res.message })
            }
        }).catch(err => {
            SnModal.error({ title: 'Algo salió mal', content: err.message })
        }).finally(e =>{
            this.setLoading(false);
        })
    },
    delete(userId, content = '') {
        let _setLoading = this.setLoading;
        let _list = this.list;

        SnModal.confirm({
            title: '¿Estás seguro de eliminar este registro?',
            content: content,
            okText: 'Si',
            okType: 'error',
            cancelText: 'No',
            onOk() {
                _setLoading(true);
                RequestApi.fetch('/api/user/delete', {
                    method: 'POST',
                    body: {
                        userId: userId || 0
                    }
                }).then(res => {
                    if (res.success) {
                        _list();
                        SnMessage.success({ content: res.message });
                    } else {
                        SnModal.error({ title: 'Algo salió mal', content: res.message })
                    }
                }).catch(err => {
                    SnModal.error({ title: 'Algo salió mal', content: err.message })
                }).finally(e => {
                    _setLoading(false);
                })
            }
        });
    },

    showModalCreate(){
        this.currentModeForm = 'create';
        this.clearForm();
        this.showModalMode('create');
        SnModal.open(this.modalName);
    },

    showModalMode(mode = ''){
        document.getElementById('userEmail').parentElement.classList.remove('hidden');
        document.getElementById('userUserName').parentElement.classList.remove('hidden');
        document.getElementById('userState').parentElement.classList.remove('hidden');
        document.getElementById('userUserRoleId').parentElement.classList.remove('hidden');
        document.getElementById('userPassword').parentElement.classList.remove('hidden');

        if (mode === 'normal'){
            document.getElementById('userPassword').parentElement.classList.add('hidden');
        } else if(mode === 'password') {
            document.getElementById('userEmail').parentElement.classList.add('hidden');
            document.getElementById('userUserName').parentElement.classList.add('hidden');
            document.getElementById('userState').parentElement.classList.add('hidden');
            document.getElementById('userUserRoleId').parentElement.classList.add('hidden');
        } else if(mode === 'create') {
            document.getElementById('userState').checked = true;
        }
    },

    executeUpdateNormal(userId){
        this.showModalMode('normal');
        this.currentModeForm = 'update';
        this.showModalUpdate(userId);
    },

    executeUpdatePassword(userId){
        this.showModalMode('password');
        this.currentModeForm = 'updatePassword';
        this.showModalUpdate(userId);
    },

    showModalUpdate(userId){
        this.clearForm();

        RequestApi.fetch('/api/user/id',{
            method: 'POST',
            body: {
                userId: userId || 0
            }
        }).then(res => {
            if (res.success){
                document.getElementById('userEmail').value  = res.result.email;
                document.getElementById('userUserName').value  = res.result.user_name;
                document.getElementById('userState').checked  = res.result.state;
                document.getElementById('userUserRoleId').value  = res.result.user_role_id;
                document.getElementById('userId').value = res.result.user_id;

                SnModal.open(this.modalName);
            }else {
                SnModal.error({ title: 'Algo salió mal', content: res.message })
            }
        }).catch(err => {
            SnModal.error({ title: 'Algo salió mal', content: err.message })
        })
    }
};

UserForm.init();
