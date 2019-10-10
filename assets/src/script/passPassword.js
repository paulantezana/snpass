//
// let PassFolderForm = {
//     currentModeForm: 'create',
//     modalName: 'passCustomerModalForm',
//
//     currentForm: document.getElementById('passCustomerForm'),
//     submitButton: document.getElementById('passCustomerFormSubmit'),
//
//     loading: false,
//
//     init(){
//
//     },
//
//     setLoading(state){
//         this.loading = state;
//         let jsPassCustomerOption = document.querySelectorAll('.jsPassCustomerOption');
//         if (this.loading){
//             if(this.submitButton){
//                 this.submitButton.setAttribute('disabled','disabled');
//                 this.submitButton.classList.add('loading');
//                 if(jsPassCustomerOption){
//                     jsPassCustomerOption.forEach(item => {
//                         item.setAttribute('disabled','disabled');
//                     });
//                 }
//             }
//         } else {
//             if(this.submitButton){
//                 this.submitButton.removeAttribute('disabled');
//                 this.submitButton.classList.remove('loading');
//                 if(jsPassCustomerOption){
//                     jsPassCustomerOption.forEach(item => {
//                         item.removeAttribute('disabled');
//                     });
//                 }
//             }
//         }
//     },
//
//     loadPassPassword(id){
//         PassPasswordForm.list(id)
//     },
//
//     search(event){
//         InfinityLoading.setSearh(event.target.value);
//     },
//
//     clearForm(){
//         if (this.currentForm){
//             this.currentForm.reset();
//         }
//     },
//
//     submit(event){
//         event.preventDefault();
//
//         let passCustomer = {
//             name: document.getElementById('passCustomerFormName').value || '',
//             description: document.getElementById('passCustomerFormDescription').value || '',
//         };
//         if (this.currentModeForm === 'update'){
//             passCustomer.passCustomerId  = document.getElementById('passCustomerId').value || 0;
//         }
//
//         this.setLoading(true);
//         const url = this.currentModeForm === 'update' ? '/api/customer/update' : '/api/customer/create';
//
//         RequestApi.fetch(url,{
//             method: 'POST',
//             body: passCustomer
//         }).then(res => {
//             if (res.success){
//                 SnModal.close(this.modalName);
//                 InfinityLoading.reload();
//                 SnMessage.success({ content: res.message });
//             } else {
//                 SnModal.error({ confirm: false, title: 'Algo salió mal', content: res.message })
//             }
//         }).catch(err => {
//             SnModal.error({ confirm: false, title: 'Algo salió mal', content: err.message })
//         }).finally(e =>{
//             this.setLoading(false);
//         })
//     },
//
//     delete(passCustomerId, content = ''){
//         let _setLoading = this.setLoading
//         SnModal.confirm({
//             title: '¿Estás seguro de eliminar este registro?',
//             content: content,
//             okText: 'Si',
//             okType: 'error',
//             cancelText: 'No',
//             onOk(){
//                 _setLoading(true);
//                 RequestApi.fetch('/api/customer/delete',{
//                     method: 'POST',
//                     body: {
//                         passCustomerId: passCustomerId || 0
//                     }
//                 }).then(res => {
//                     if (res.success){
//                         InfinityLoading.reload();
//                         SnMessage.success({ content: res.message });
//                     }else {
//                         SnModal.error({ title: 'Algo salió mal', content: res.message })
//                     }
//                 }).catch(err => {
//                     SnModal.error({ title: 'Algo salió mal', content: err.message })
//                 }).finally(e=>{
//                     _setLoading(false);
//                 })
//             }
//         });
//     },
//
//     showModalCreate(){
//         SnModal.open(this.modalName);
//         this.clearForm();
//         this.currentModeForm = 'create';
//     },
//
//     showModalUpdate(passCustomerId,content){
//         this.currentModeForm = 'update';
//         this.setLoading(true);
//
//         RequestApi.fetch('/api/customer/id',{
//             method: 'POST',
//             body: {
//                 passCustomerId: passCustomerId || 0
//             }
//         }).then(res => {
//             if (res.success){
//                 document.getElementById('passCustomerId').value = res.result.pass_customer_id || '';
//                 document.getElementById('passCustomerFormName').value = res.result.name || '';
//                 document.getElementById('passCustomerFormDescription').value = res.result.description || '';
//                 SnModal.open(this.modalName);
//             }else {
//                 SnModal.confirm({ title: 'Algo salió mal', content: res.message })
//             }
//         }).catch(err => {
//             SnModal.confirm({ title: 'Algo salió mal', content: err.message })
//         }).finally(e=>{
//             this.setLoading(false);
//         })
//     }
// }
// PassCustomerForm.init();