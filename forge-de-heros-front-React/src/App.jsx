import { BrowserRouter as Router, Routes, Route,  useLocation } from "react-router-dom"
import Characters from "./pages/Characters"
import Home from "./pages/Home";
import CharacterDetail from "./pages/CharacterDetail";

function AppRoutes() {
  const location = useLocation();

  return (
    <Routes location={location} key={location.pathname}>
        <Route path="/" element={<Home />} />
        <Route path="/characters" element={<Characters />} />
        <Route path="/characters/:id" element={<CharacterDetail />} />
        <Route path="/parties" element={<div>Parties</div>} />
    </Routes>
  )
}

function App() {
  return (
    <Router>
        <AppRoutes />
    </Router>
  );
}

export default App
