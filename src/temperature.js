const { Component } = wp.element;

class Temperature extends Component {
	constructor(props) {
		super(props);
		this.state = {
			temperature: '',
			units: 'imperial',
		}
	}
 
	componentDidMount() {
		this.runApiFetch();
	}
 
	runApiFetch() {
		wp.apiFetch({
			path: 'ama-weather/v1/temperature?units=' + this.state.units,
		}).then(data => {
			this.setState({
				temperature: data
			});
		});
	}
 
	render() {
		const { temperature } = this.state;

		return(
			<div dangerouslySetInnerHTML={{ __html: temperature }} />
		);
 
	}
}
export default Temperature;