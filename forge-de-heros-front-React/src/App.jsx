import { BrowserRouter as Router, Routes, Route,  useLocation } from "react-router-dom"
import Characters from "./pages/Characters"
import Home from "./pages/Home";
import Parties from "./pages/Parties";

function AppRoutes() {
  const location = useLocation();

  return (
    <Routes location={location} key={location.pathname}>
        <Route path="/" element={<Home />} />
        <Route path="/characters" element={<Characters />} />
        <Route path="/parties" element={<Parties/>} />
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
