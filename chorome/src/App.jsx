import { useContext, useState } from 'react'
import './App.css'
// import { isAuth } from './core/Auth';
import Login from './modules/login/Login';
import { useApp } from './AppContext';
import Note from './modules/note/Note';

function App() {
  const appData = useApp()
  const [navName, setNavName] = useState('PS');

  return <>
    { appData.isAuth ? <div>
      <div className='nav'>
        <div className='nav-head'>
          <div onClick={()=>setNavName('PS')} >PS</div>
          <div onClick={()=>setNavName('NT')} >NT</div>
        </div>
        <div className='nav-body'>
          {/* { navName === 'PS' && <Pass/> } */}
          { navName === 'NT' && <Note/> }
        </div>
      </div>

    </div> : <Login/> }
  </>
}

export default App
