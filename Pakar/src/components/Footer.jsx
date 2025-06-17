// src/components/Footer.jsx
import React from "react";
import "./Footer.css";

const Footer = () => {
  return (
    <footer>
      <div className="footer-top-section">
        <div className="container">
          {/* Bagian PAKAR! dan WEBSITE */}
          <div className="footer-content">
            <div className="footer-column">
              <h3>PAKAR!</h3>
              <p>Layanan ini memudahkan pelaporan kerusakan aset dan ruangan perkuliahan agar dapat segera ditindaklanjuti demi terciptanya lingkungan belajar yang nyaman dan layak.</p>
            </div>
            <div className="footer-column website-info">
              <h3>WEBSITE</h3>
              <p>Front end:</p>
              <p>Muhammad Zia Ul-Haq</p>
              <p>Naela Almira Najwa Efendi</p>
              <br />
              <p>UI/UX Design :</p>
              <p>Rifa Fadhilah</p>
              <br />

              <p>Back End:</p>
              <p>Fadlan Rizky Lubis</p>
              <p>Zaviandra Chalil</p>
            </div>
          </div>

          
        </div>
      </div>

      {/* Bagian Copyright */}
      <div className="footer-bottom-section">
        <p>© Kelompok 5 Sistem Basis data</p>
      </div>
    </footer>
  );
};

export default Footer;
