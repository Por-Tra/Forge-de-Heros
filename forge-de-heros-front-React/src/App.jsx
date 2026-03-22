import {
  BrowserRouter as Router,
  Routes,
  Route,
  useLocation,
} from 'react-router-dom';
import Characters from './pages/Characters';
import Home from './pages/Home';
import CharacterDetail from './pages/CharacterDetail';
import Parties from './pages/Parties';
import PartyDetail from './pages/PartyDetail';
import './styles/App.scss';

function AppRoutes() {
  const location = useLocation();

  return (
    <Routes location={location} key={location.pathname}>
      <Route path="/" element={<Home />} />
      <Route path="/characters" element={<Characters />} />
      <Route path="/characters/:id" element={<CharacterDetail />} />
      <Route path="/parties" element={<Parties />} />
      <Route path="/parties/:id" element={<PartyDetail />} />
    </Routes>
  );
}

function App() {
  return (
    <Router>
      <AppRoutes />
    </Router>
  );
}

export default App;
