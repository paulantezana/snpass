import { useEffect, useState } from "react"
import { RequestApi } from "../../core/RequestApi";
import { useApp } from "../../AppContext";
import NoteForm from "./NoteForm";

const NoteList = ({item}) => {
  return (<div>
    <div>{item.description}</div>
  </div>)
}

function Note() {
  const [data, setData] = useState([]);
  const [search, setSearch] = useState('');
  const [loading, setLoading] = useState(false);
  const appData = useApp();

  const loadData = async () => {
    setLoading(true);
    const res = await RequestApi.fetch('note/all');
    if (res?.success) {
      setData(res.data);
    }
    setLoading(false);
  }

  useEffect(() => {
    loadData();
  }, []);

  const handleSearch = () => {
    setSearch('');
    // loadData();
  }

  return (
    <div>
      <input type="search" onChange={handleSearch} />
      {
        data?.map(item => <NoteList key={item.id} item={item} />)
      }
      <NoteForm />
    </div>
  )
}

export default Note
