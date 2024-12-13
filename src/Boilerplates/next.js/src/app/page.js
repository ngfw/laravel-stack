import LoginLinks from '@/app/LoginLinks';
import LaravelStack from '@/components/LaravelStack';

export const metadata = {
    title: 'Laravel + Next.js',
}

const Home = () => {
    return (
        <>
            <div className="home-page relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
                <LoginLinks />
                <LaravelStack />
            </div>
        </>
    )
}

export default Home
