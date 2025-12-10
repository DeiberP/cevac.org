import { useState, useEffect } from "react";
import { LoadingScreen } from "./components/atoms/SpinnerAtom";
import Menu from "./components/organismos/HeaderComponent";
import Carousel from "./components/organismos/CarrouselComponet";
import "./App.css"

function App() {
  const [isLoading, setIsLoading] = useState(true);
  const [isFadingOut, setIsFadingOut] = useState(false);
  useEffect(() => {
    const loadTimer = setTimeout(() => {
      setIsFadingOut(true);
    }, 2000);

    const fadeTimer = setTimeout(() => {
      setIsLoading(false);
    }, 2500);
    return () => {
      clearTimeout(loadTimer);
      clearTimeout(fadeTimer);
    };
  }, []);

  return (
    <div>
      {isLoading && <LoadingScreen isFadingOut={isFadingOut} />}
      {!isLoading && (
        <main id="main-content">
          <Menu></Menu>
          <section>
            <Carousel></Carousel>
          </section>
          
        </main>
      )}
    </div>
  );
}

export default App;
