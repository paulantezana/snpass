import { createContext, useContext, useReducer } from 'react';
import { isAuth, removeToken, saveToken } from './core/Auth';

const AppContext = createContext(null);

const AppDispatchContext = createContext(null);

export function AppProvider({ children }) {
  const [app, dispatch] = useReducer(
    appReducer,
    {
      isAuth: isAuth(),
    }
  );

  return (
    <AppContext.Provider value={app}>
      <AppDispatchContext.Provider value={dispatch}>
        {children}
      </AppDispatchContext.Provider>
    </AppContext.Provider>
  );
}

export function useApp() {
  return useContext(AppContext);
}

export function useAppDispatch() {
  return useContext(AppDispatchContext);
}

function appReducer(app, action) {
  switch (action.type) {
    case 'login': {
      saveToken(action.token);
      return { ...app, isAuth: true, token: action.token , user: action.user }
    }
    case 'logout': {
      removeToken();
      return { ...app, isAuth: false, token: null, user: {} }
    }
    default: {
      throw Error('Unknown action: ' + action.type);
    }
  }
}