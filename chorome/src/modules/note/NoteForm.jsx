import { useState } from "react";
import { RequestApi } from "../../core/RequestApi";
import { useAppDispatch } from "../../AppContext";

function NoteForm() {
    const [name, setName] = useState("");
    const [description, setDescription] = useState("");
    const appDispatch = useAppDispatch()

    const handleNameChange = (event) => {
        setName(event.target.value);
    };

    const handleDescriptionChange = (event) => {
        setDescription(event.target.value);
    };

    const handleSubmit = async (event) => {
        event.preventDefault();
        const res = await RequestApi.fetch('note/create', { method: 'POST', body: { description } });
        if(res?.success){
        }
    };

    return (
        <>
            <form onSubmit={handleSubmit}>

                <input type="text" required value={name} onChange={handleNameChange} />

                <input type="text" required value={description} onChange={handleDescriptionChange} />

                <button type="submit">Guardar</button>
            </form>
        </>
    )
}

export default NoteForm