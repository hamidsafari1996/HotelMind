import Header from './components/Header';
import Slider from './components/Slider';
import TravelTypes from './components/TravelTypes';
import PopularDestinations from './components/PopularDestinations';
import Ferienwohnungen from './components/Ferienwohnungen';
import BesteurlaubS from './components/besteurlaubS';
import BesteurlaubM from './components/besteurlaubM';
import Beliebtestadt from './components/Beliebtestadt';
import Reisewelt from './components/Reisewelt';
import Footer from './components/Footer';
import { HydrationProvider } from './components/UI/HydrationProvider';

export default function Home() {
  return (
    <HydrationProvider>
      <Header />
      <main className='bg-slate-100' suppressHydrationWarning={true}>
        <Slider />
        <TravelTypes />
        <PopularDestinations />
        <Ferienwohnungen />
        <BesteurlaubM />
        <BesteurlaubS />
        <Beliebtestadt />
        <Reisewelt />
      </main>
      <Footer />
    </HydrationProvider>
  );
}
