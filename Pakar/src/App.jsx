import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import HeroSection from "./components/HeroSection";
import FiturUnggulan from "./components/FiturUnggulan";
import Footer from "./components/Footer";
import RiwayatLaporan from "./components/RiwayatLaporan";
import GedungPerkuliahan from "./components/GedungPerkuliahan";
import LoginPage from "./components/LoginPage";
import ReportForm from "./components/ReportForm";

function App() {
  return (
    <Router>
      <Routes>
        <Route
          path="/"
          element={
            <>
              <HeroSection />
              <FiturUnggulan />
              <GedungPerkuliahan />
              <Footer />
            </>
          }
        />
        <Route path="/riwayat" element={<RiwayatLaporan />} />
        <Route path="/login" element={<LoginPage />} />
        <Route path="/report" element={<ReportForm />} />
      </Routes>
    </Router>
  );
}

export default App;
