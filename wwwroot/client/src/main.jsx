import {BrowserRouter} from "react-router-dom"
import { createRoot } from 'react-dom/client'
import App from './App.jsx'
import "./router/routes.jsx"
import 'bootstrap/dist/css/bootstrap.min.css';

createRoot(document.getElementById('root')).render(
  <BrowserRouter>
    <App />
  </BrowserRouter>
)
