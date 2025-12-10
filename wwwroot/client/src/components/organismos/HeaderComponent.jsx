// src/components/Menu.jsx
import React, { useState, useEffect, useRef } from 'react';
import "../organismos/style/Header.css";
import 'bootstrap/dist/css/bootstrap.min.css';

import LogoCevac from "../../assets/img/LogoCevac.png";
import CirculoFlecha from "../../assets/img/CirculoFlecha.png";
import IconoCierre from "../../assets/img/Hamburger.png";


const Menu = () => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [isScrolled, setIsScrolled] = useState(false);
  const [isNestingOpen, setIsNestingOpen] = useState(false);
  const [arrowAnimate, setArrowAnimate] = useState(false);

  const navRef = useRef(null);
  const linksRef = useRef(null);
  const nestingRef = useRef(null);

  useEffect(() => {
    const handleScroll = () => setIsScrolled(window.scrollY > 50);
    window.addEventListener('scroll', handleScroll);

    const handleResize = () => {
      if (window.innerWidth >= 992) {
        // reset mobile states on desktop
        setIsMenuOpen(false);
        document.body.style.overflow = '';
        if (nestingRef.current) nestingRef.current.style.height = '';
      }
    };
    window.addEventListener('resize', handleResize);

    const handleClickOutside = (e) => {
      if (!navRef.current) return;
      if (isMenuOpen && !navRef.current.contains(e.target)) {
        setIsMenuOpen(false);
        document.body.style.overflow = '';
      }
      if (isNestingOpen && !navRef.current.contains(e.target)) {
        setIsNestingOpen(false);
      }
    };
    document.addEventListener('click', handleClickOutside);

    return () => {
      window.removeEventListener('scroll', handleScroll);
      window.removeEventListener('resize', handleResize);
      document.removeEventListener('click', handleClickOutside);
    };
  }, [isMenuOpen, isNestingOpen]);

  useEffect(() => {
    // lock body scroll when mobile menu open
    document.body.style.overflow = isMenuOpen ? 'hidden' : '';
  }, [isMenuOpen]);

  useEffect(() => {
    const submenu = nestingRef.current;
    if (!submenu) return;
    if (isNestingOpen && window.innerWidth < 992) {
      submenu.style.height = submenu.scrollHeight + 'px';
      // animate arrow pop once
      setArrowAnimate(true);
      const t = setTimeout(() => setArrowAnimate(false), 300);
      return () => clearTimeout(t);
    } else {
      submenu.style.height = '0';
    }
  }, [isNestingOpen]);

  const toggleMenu = () => setIsMenuOpen(prev => !prev);

  const toggleNesting = (e) => {
    // only intercept on mobile / small screens
    if (window.innerWidth <= 992) {
      e.preventDefault();
      setIsNestingOpen(prev => !prev);
    }
  };

  const handleNestingKey = (e) => {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      setIsNestingOpen(prev => !prev);
    } else if (e.key === 'Escape') {
      setIsNestingOpen(false);
    }
  };

  const menuClasses = `menu w-100 ${isScrolled ? 'scrolled' : ''}`;
  const linksClasses = `menu__links d-flex flex-column flex-lg-row align-items-start align-items-lg-center ${isMenuOpen ? 'menu__links--show' : ''}`;
  const ocupacionesItemClasses = `menu__item ${isNestingOpen ? 'menu__item--active' : ''}`;

  return (
    <header className="stick">
      <nav className={menuClasses} id="main-nav" ref={navRef} aria-label="Main navigation">
        <div className="container h-100 d-flex justify-content-between align-items-center">
          <div className="menu__logo d-flex align-items-center">
            <img
              src={LogoCevac}
              className="logo_p"
              width="200"
              height="57"
              alt="Logo de la empresa"
            />
          </div>
          <button
            className="menu__toggle d-lg-none"
            aria-expanded={isMenuOpen}
            aria-controls="main-menu-links"
            aria-label={isMenuOpen ? 'Cerrar menú' : 'Abrir menú'}
            onClick={toggleMenu}
            type="button"
            style={{ background: 'transparent', border: 0 }}
          >
            <img src={IconoCierre} alt={isMenuOpen ? 'Cerrar' : 'Abrir'} width="36" height="36" />
          </button>

          <ul id="main-menu-links" className={linksClasses} ref={linksRef}>
            <li className="menu__item h-lg-100 d-flex align-items-center w-100 w-lg-auto">
              <a href="#" className="menu__link d-flex align-items-center text-decoration-none text-white fw-bold w-100">Inicio</a>
            </li>

            <li className="menu__item h-lg-100 d-flex align-items-center w-100 w-lg-auto">
              <a href="html/Sobre_Nosotros.html" className="menu__link d-flex align-items-center text-decoration-none text-white fw-bold w-100">Sobre Nosotros</a>
            </li>

            <li
              className={`h-lg-100 d-flex flex-column flex-lg-row align-items-start align-items-lg-center w-100 w-lg-auto ${ocupacionesItemClasses}`}
              tabIndex={0}
              onKeyDown={handleNestingKey}
            >
              <a
                href="#"
                className="menu__link d-flex justify-content-between align-items-center text-decoration-none text-white fw-bold w-100"
                aria-haspopup="true"
                aria-expanded={isNestingOpen}
                onClick={toggleNesting}
              >
                <span>Ocupaciones</span>

                {/* Flecha SVG inline para rotar con CSS */}
                <span className={`menu__arrow ${arrowAnimate ? 'animate-pop' : ''}`} aria-hidden="true">
                    <img src={CirculoFlecha} alt="Icono de flecha" />
                </span>
              </a>

              <ul className="menu__nesting p-0 m-0 w-100" ref={nestingRef} aria-label="Submenu ocupaciones">
                <li className="menu__inside">
                  <a href="html/Analista_contable.html" className="menu__link menu__link--inside text-decoration-none text-white fw-bold">Analista Contable</a>
                </li>
                <li className="menu__inside">
                  <a href="html/Administrador_Plataformas_digitales.html" className="menu__link menu__link--inside text-decoration-none text-white fw-bold">Plataformas Digitales</a>
                </li>
                <li className="menu__inside">
                  <a href="html/Desarrollador_Sofware_Aplicaiones.html" className="menu__link menu__link--inside text-decoration-none text-white fw-bold">Desarrollador de Software y Aplicaciones</a>
                </li>
                <li className="menu__inside">
                  <a href="html/Secretario_Administrativo.html" className="menu__link menu__link--inside text-decoration-none text-white fw-bold">Secretario Administrativo</a>
                </li>
                <li className="menu__inside">
                  <a href="html/Ventas_Vendedor.html" className="menu__link menu__link--inside text-decoration-none text-white fw-bold">Vendedor/Ventas</a>
                </li>
              </ul>
            </li>

            <li className="menu__item h-lg-100 d-flex align-items-center w-100 w-lg-auto">
              <a href="#footer" className="menu__link d-flex align-items-center text-decoration-none text-white fw-bold w-100">Contactos</a>
            </li>

            <li className="menu__item h-lg-100 d-flex align-items-center w-100 w-lg-auto">
              <a href="Formulario.php" className="menu__link d-flex align-items-center text-decoration-none text-white fw-bold w-100">Postulación</a>
            </li>
          </ul>
        </div>
      </nav>
    </header>
  );
};

export default Menu;
