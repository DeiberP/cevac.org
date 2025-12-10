import "../atoms/style/Spinner.css"
export const Spinner = () => {
    return (
        <div className="spinner">
        </div>
    );
};
export const LoadingScreen = ({ isFadingOut }) => {
    const screenClass = isFadingOut ? "loading-screen fade-out" : "loading-screen";
    return (
        <div className={screenClass}>
            <div className="spinner"></div>
        </div>
    );
};