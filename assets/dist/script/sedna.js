const SnActiveMenu = (links = []) => {
    if (links) {
        links.map(link => {
            const url = document.location.href;
            if (link.href === url || link.href === url.slice(0, -1))
                link.parentNode.classList.add('is-active');
        });
    }
    return links;
};

const SnMenu = ({
    menuId = "Menu",
    toggleButtonID = "Menu-toggle",
    contextId = "Site",
    toggleClass = "Menu-is-show",
    parentClose = false,
    menuCloseID = '',
    wrapperId = '',
}) => {
    // Get Menu
    let menuEl = document.getElementById(menuId);
    if (!menuEl) return menuEl;

    // Sub menus dinamicos
    let items = menuEl.querySelectorAll("li"); // select all items
    for (let ele of items) {
        if (ele.childElementCount === 2) {
            // if submenu
            let toggle = ele.firstElementChild; // First Element
            let content = ele.lastElementChild; // Second Element

            // Creando un nuevo elemento e insertando justo despues del enlace
            let iconToggleEle = document.createElement("i");
            iconToggleEle.classList.add("icon-down");
            iconToggleEle.classList.add("Toggle");
            toggle.appendChild(iconToggleEle);
            toggle.classList.add('is-toggle')

            // Escuchando los eventos click
            iconToggleEle.addEventListener("click", e => {
                e.preventDefault();
                iconToggleEle.classList.toggle("icon-up"); // add Icon up
                content.classList.toggle("is-show"); // add class show menu
            });
        }
    }

    // Agregar la clase active en los enlaces
    SnActiveMenu([...menuEl.querySelectorAll('a')]);

    // Toggle Menu
    let button = document.getElementById(toggleButtonID);
    let context = document.getElementById(contextId);
    if (button && context) {
        button.addEventListener("click", () => {
            context.classList.toggle(toggleClass);
        });
    }

    // Menu close quitar la clase
    if (menuCloseID !== '') {
        let menuClose = document.getElementById(menuCloseID);
        if (menuClose) {
            menuClose.addEventListener("click", () => {
                context.classList.remove(toggleClass);
            });
        }
    }

    // Menu close quitar la clase
    if (wrapperId !== '') {
        let wrapper = document.getElementById(wrapperId);
        if (wrapper) {
            wrapper.addEventListener("click", () => {
                context.classList.remove(toggleClass);
            });
        }
    }

    // Hide menu by parent
    if (parentClose) {
        menuEl.parentNode.addEventListener("click", e => {
            context.classList.remove(toggleClass);
        });
        menuEl.addEventListener('click', e => {
            e.stopPropagation();
        })
    }
    return menuEl;
};

const SnUniqueId = (length = 6) => {
    let timestamp = +new Date;

    let _getRandomInt = function (min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    };

    let ts = timestamp.toString();
    let parts = ts.split("").reverse();
    let id = "";
    for (let i = 0; i < length; ++i) {
        let index = _getRandomInt(0, parts.length - 1);
        id += parts[index];
    }
    return id;
};

// -------------------------------------------------------------------------
// -------------------------------- MODAL ----------------------------------
let SnModalContain = document.createElement('div');
SnModalContain.classList.add('SnModalContain');
const SnModalApi = () => {
    // let modalWrapper = null
    let dataModals = null;
    let openModals = [];


    let closeModalsOnEsc = () => {
        window.addEventListener('keyup', (event) => {
            if (openModals.length && event.keyCode === 27) {
                api.closeLastModal()
            }
        })
    };

    let closeModal = (m) => {
        m.classList.remove('visible')

        // Re-enable parent scrolling
        document.body.style.overflow = 'auto'
    };

    let api = {
        init() {
            // modalWrapper = document.querySelector('.SnModal-wrapper')
            dataModals = document.querySelectorAll('[data-modal]')
            for (let i = 0; i < dataModals.length; i++) {
                dataModals[i].addEventListener('click', (event) => {

                    let modalName = dataModals[i].dataset.modal
                    api.close(modalName)
                })
            }

            // Modal button trigger open
            let triggers = document.querySelectorAll('[data-modaltrigger]')
            for (let i = 0; i < triggers.length; i++) {
                triggers[i].addEventListener('click', (event) => {
                    let modalName = triggers[i].dataset.modaltrigger
                    api.open(modalName)
                })
            }

            // Modal button close
            let closes = document.querySelectorAll('[data-modalclose]')
            for (let i = 0; i < closes.length; i++) {
                closes[i].addEventListener('click', (event) => {
                    let modalName = closes[i].dataset.modalclose
                    api.close(modalName)
                })
            }

            closeModalsOnEsc()
        },

        open(modalName, cb) {
            let modal = document.querySelector(`[data-modal="${modalName}"]`)

            // If modal is already open, don't do anything
            if (openModals.indexOf(modal) >= 0) return

            if (modal) {
                modal.classList.add('visible')

                // Modal prevent events
                let modalContent = modal.querySelector('.SnModal');
                if (modalContent) {
                    modalContent.addEventListener('click', (event) => {
                        event.stopPropagation()
                    })
                }

                // Disable parent scrolling when modal is open
                document.body.style.overflow = 'hidden'

                openModals.push(modal)
            } else {
                console.error('Could not find modal with name "%s"', modalName)
            }

            typeof cb === 'function' && cb()
        },

        close(modalName, cb) {
            let modal = document.querySelector(`[data-modal="${modalName}"]`)

            // If modal is already open, don't do anything
            // if (openModals.indexOf(modal) >= 0) return

            if (modal) {
                closeModal(modal)

                openModals.pop(modal)
            } else {
                console.error('Could not find modal with name "%s"', modalName)
            }

            typeof cb === 'function' && cb()
        },

        closeLastModal(cb) {
            let modal = openModals.pop()
            closeModal(modal)
            typeof cb === 'function' && cb()
        },

        confirm({
            confirm = true,
            title = '',
            content = '',
            okType = 'primary',
            cancelType = '',
            cancelText = 'Cancelar',
            okText = 'OK',
            onOk = () => { },
            onCancel = () => { }
        }) {
            let uniqueIdName = 'Sn' + SnUniqueId();
            let divEl = document.createElement('div');

            let cancelTemp = confirm
                ? `<div class="SnBtn ${cancelType}" id="cancel${uniqueIdName}">${cancelText}</div>`
                : '';

            divEl.innerHTML = `
                <div class="SnModal-wrapper" data-modal="${uniqueIdName}" >
                    <div class="SnModal confirm">
                        <div class="SnModal-body confirm">
                            <div class="SnModal-confirmTile">${title}</div>
                            <div class="SnModal-confirmContent">${content}</div>
                            <div class="SnModal-confirmBtns">
                                ${cancelTemp}
                                <div class="SnBtn ${okType}" id="ok${uniqueIdName}">${okText}</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            SnModalContain.appendChild(divEl);
            api.open(uniqueIdName);

            let btnCancel = document.getElementById(`cancel${uniqueIdName}`);
            if (btnCancel) {
                btnCancel.addEventListener('click', e => {
                    e.preventDefault();
                    api.close(uniqueIdName);
                    SnModalContain.removeChild(divEl);
                    onCancel();
                });
            }

            let btnOk = document.getElementById(`ok${uniqueIdName}`);
            if (btnOk) {
                btnOk.addEventListener('click', e => {
                    e.preventDefault();
                    api.close(uniqueIdName);
                    SnModalContain.removeChild(divEl);
                    onOk();
                });
            }
        }
    }

    window.snModalApi = api
};
class SnModal {
    static open(modalName, cb){
        snModalApi.open(modalName, cb);
    }

    static close(modalName, cb){
        snModalApi.close(modalName, cb);
    }

    static info({ 
        title = '',
        content = '',
        okText = 'OK',
        onOk = () => { },
     }) {
        window.snModalApi.confirm({
            confirm: false,
            title,
            content,
            okText,
            onOk,
        });
    }
    static success({ 
        title = '',
        content = '',
        okText = 'OK',
        onOk = () => { },
     }) {
        window.snModalApi.confirm({
            confirm: false,
            title,
            content,
            okText,
            onOk,
        });
    }
    static error({ 
        title = '',
        content = '',
        okText = 'OK',
        onOk = () => { },
     }) {
        window.snModalApi.confirm({
            confirm: false,
            title,
            content,
            okText,
            onOk,
        });
    }
    static warning({
        title = '',
        content = '',
        okText = 'OK',
        onOk = () => { },
     }) {
        window.snModalApi.confirm({
            confirm: false,
            title,
            content,
            okText,
            onOk,
        });
    }
    static confirm({
        title = '',
        content = '',
        okType = 'primary',
        cancelType = '',
        cancelText = 'Cancelar',
        okText = 'OK',
        onOk = () => { },
        onCancel = () => { }
    }) {
        window.snModalApi.confirm({
            confirm: true,
            title,
            content,
            okType,
            cancelType,
            cancelText,
            okText,
            onOk,
            onCancel
        });
    }
}

// -------------------------------------------------------------------------
// -------------------------------- MESSAGE --------------------------------
const transitionLength = 700;
let SnMessageContain = document.createElement('div');
SnMessageContain.classList.add('SnMessageContain');
class SnMessage {
    static success({ content = '', duration = 3000 }) {
        this.message(content, duration, 'success', 'icon-success');
    }
    static error({ content = '', duration = 3000 }) {
        this.message(content, duration, 'error', 'icon-error');
    }
    static warning({ content = '', duration = 3000 }) {
        this.message(content, duration, 'warning', 'icon-warning');
    }
    static message(message, time, addClass = 'default', icon = "") {
        if (!time || time === 'default') {
            time = 2000;
        }
        let messagetEl = document.createElement('div');
        messagetEl.classList.add('SnMessage', addClass, icon);
        messagetEl.innerText = message;
        SnMessageContain.prepend(messagetEl);
        setTimeout(() => messagetEl.classList.add('open'));
        setTimeout(
            () => messagetEl.classList.remove('open'),
            time
        );
        setTimeout(
            () => SnMessageContain.removeChild(messagetEl),
            time + transitionLength
        );
    }
}

// -------------------------------------------------------------------------
// -------------------------------------------------------------------------
// Execute
document.addEventListener("DOMContentLoaded", e => {
    document.body.appendChild(SnModalContain);
    document.body.appendChild(SnMessageContain);

    SnModalApi();
    snModalApi.init();
});