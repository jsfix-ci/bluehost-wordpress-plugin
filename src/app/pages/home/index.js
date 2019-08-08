/**
 * External dependencies
 */
import AppPage from '@/components/app-page';
import './style.scss';

/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */
import ContentSection from './content/';
import DesignBuildSection from './design-build/';
import TrafficEngagementSection from './traffic-engagement/';
import PerformanceSection from './performance/';
import HostingSection from './hosting/';
import ComingSoonNotice from './coming-soon-notice/';

const HomePage = () => (
	<AppPage>
		<div className="welcome-section">
			<h1>Welcome to your WordPress site!</h1>
			<p>From here, you can quickly add content to your site, manage for-sale products, work on your site’s design and performance, manage hosting, and access tools to increase your traffic.</p>
		</div>
		<ComingSoonNotice />
		<ContentSection />
		<DesignBuildSection />
		<TrafficEngagementSection />
		<PerformanceSection />
		<HostingSection />
	</AppPage>
);

export default HomePage;