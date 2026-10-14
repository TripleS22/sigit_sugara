import LogoLoop from '../reactbits/LogoLoop.jsx';

// LogoLoop expects logo objects; Blade passes a plain list of technology names.
export default function TechLoop({ items = [], ...props }) {
    const logos = items.map((name) => ({
        node: <span className="text-lg font-semibold whitespace-nowrap text-slate-500">{name}</span>,
        title: name,
    }));

    return (
        <LogoLoop
            logos={logos}
            speed={60}
            gap={48}
            logoHeight={28}
            pauseOnHover
            fadeOut
            fadeOutColor="#ffffff"
            ariaLabel="Teknologi yang kami gunakan"
            {...props}
        />
    );
}
