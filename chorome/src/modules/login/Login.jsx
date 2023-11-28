import { useState } from "react";
import { RequestApi } from "../../core/RequestApi";
import { useAppDispatch } from "../../AppContext";

function Login() {
    const [username, setUsername] = useState("");
    const [password, setPassword] = useState("");
    const appDispatch = useAppDispatch()

    const handleUsernameChange = (event) => {
        setUsername(event.target.value);
    };

    const handlePasswordChange = (event) => {
        setPassword(event.target.value);
    };

    const handleSubmit = async (event) => {
        event.preventDefault();
        const res = await RequestApi.fetch('user/login', { method: 'POST', body: { user_name: username, password } });
        if (res?.success) {
            appDispatch({
                type: 'login',
                token: res.data.token,
                user: res.data.user,
            })
        }
    };

    return (
        <>
            <form onSubmit={handleSubmit}>
                <div className="SnForm-item required inner">
                    <label htmlFor="username" className="SnForm-label">Usuario</label>
                    <input type="text" id="username" className="SnForm-control SnControl sm" required value={username} onChange={handleUsernameChange} />
                </div>
                <div className="SnForm-item required inner">
                    <label htmlFor="password" className="SnForm-label">Contraseña</label>
                    <input type="password" id="password" className="SnForm-control SnControl sm" required value={password} onChange={handlePasswordChange} />
                </div>
                <button type="submit" className="SnBtn primary lg block">Iniciar Sesión</button>
            </form>
        </>
    )
}

export default Login