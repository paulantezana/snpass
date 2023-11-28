export const isAuth = () => {
    const key = sessionStorage.getItem('JKDLL');
    if (!key) {
        return false;
    }
    return true;
}

export const saveToken = (value) => {
    sessionStorage.setItem('JKDLL', value);
}

export const getToken = () => {
    const value = sessionStorage.getItem('JKDLL');
    if (!value) {
        return '';
    }
    return value;
}

export const removeToken = () => {
    sessionStorage.removeItem('JKDLL');
}

