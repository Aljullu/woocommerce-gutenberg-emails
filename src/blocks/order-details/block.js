/**
 * WordPress dependencies
 */
import { Component } from '@wordpress/element';

/**
 * Component to handle edit mode of "Featured Product".
 */
class OrderDetails extends Component {
	renderEditMode() {
		return (
			<span>
				{ 'Edit Lorem ipsum' }
			</span>
		);
	}

	render() {
		return (
			<span className="lorem-ipsum">
				{ 'Lorem ipsum' }
			</span>
		);
	}
}

export default OrderDetails;
