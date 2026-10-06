import { BrowserRouter, Navigate, Route, Routes } from 'react-router-dom'
import Home from './pages/Home'
import Signup from './pages/Signup'
import Login from './pages/Login'
import CreatorDashboard from './pages/CreatorDashboard'
import CreatorProfile from './pages/CreatorProfile'
import CreatorTools from './pages/CreatorTools'
import Portfolio from './pages/Portfolio'
import AddPortfolioProject from './pages/AddPortfolioProject'
import BrandDashboard from './pages/BrandDashboard'
import CreateBrief from './pages/CreateBrief'
import ExploreCreators from './pages/ExploreCreators'
import InformationPage from './pages/InformationPage'

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/signup" element={<Signup />} />
        <Route path="/login" element={<Login />} />
        <Route path="/creator/dashboard" element={<CreatorDashboard />} />
        <Route path="/creator/profile" element={<CreatorProfile />} />
        <Route path="/creator/profile/:id" element={<CreatorProfile />} />
        <Route path="/creator/tools" element={<CreatorTools />} />
        <Route path="/creator/portfolio" element={<Portfolio />} />
        <Route path="/creator/portfolio/add" element={<AddPortfolioProject />} />
        <Route path="/brand/dashboard" element={<BrandDashboard />} />
        <Route path="/brand/brief" element={<CreateBrief />} />
        <Route path="/brand/creators" element={<ExploreCreators />} />
        <Route path="/about" element={<InformationPage />} />
        <Route path="/privacy" element={<InformationPage />} />
        <Route path="/terms" element={<InformationPage />} />
        <Route path="/support" element={<InformationPage />} />
        <Route path="*" element={<Navigate to="/" replace />} />
      </Routes>
    </BrowserRouter>
  )
}

export default App