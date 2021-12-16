import React from 'react';
import Appreciation from '../../components/Appreciation';

class AppreciationEvent extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            vues: 0,
            jaimes: 0,
            jaimepas: 0,
            comentaires: 0,
            favoris: 0,
            jaimeClass: false,
            jaimePasClass: false,
        };
    }

    componentDidMount() {
        fetch('/' + window.locale + '/event/json_event/' + window.eventEventId)
            .then(response => response.json())
            .then(entries => {
                console.log('article...',entries)
              this.setState({
                    vues: entries.vues,
                    jaimes: entries.jaime,
                    jaimepas: entries.jaimepas,
                    comentaires: entries.comments.length,
                });
            });

        //on voit si le user a deja aimé
        fetch('/' + window.locale + '/event/json_eventaime/' + window.eventEventId)
            .then(response => response.json())
            .then(entries => {
                this.setState({
                    jaimeClass: entries,
                });
            });

        fetch('/' + window.locale + '/event/json_eventaimepas/' + window.eventEventId)
            .then(response => response.json())
            .then(entries => {
                this.setState({
                    jaimePasClass: entries,
                });
            });
    }

    handleJaimesClick() {
        if (!this.state.jaimeClass) {
            if (this.state.jaimePasClass) {
                this.setState({
                    jaimePasClass: false,
                    jaimepas: this.state.jaimepas - 1,
                });
            }
        }

        this.setState({
            jaimeClass: !this.state.jaimeClass,
        });


        fetch('/' + window.locale + '/event/json_manageeventjaime/' + window.eventEventId)
            .then(response => response.json())
            .then(entries => {
                if (this.state.jaimeClass) {
                    this.setState({
                        jaimes: this.state.jaimes + 1,
                    });
                } else {
                    this.setState({
                        jaimes: this.state.jaimes - 1,
                    });
                }
            });
    }

    handleJaimePasClick() {
        if (!this.state.jaimePasClass) {
            if (this.state.jaimeClass) {
                this.setState({
                    jaimeClass: false,
                    jaimes: this.state.jaimes - 1,
                });
            }
        }

        this.setState({
            jaimePasClass: !this.state.jaimePasClass,
        });


        fetch('/' + window.locale + '/event/json_manageeventjaimepas/' + window.eventEventId)
            .then(response => response.json())
            .then(entries => {
                if (this.state.jaimePasClass) {
                    this.setState({
                        jaimepas: this.state.jaimepas + 1,
                    });
                } else {
                    this.setState({
                        jaimepas: this.state.jaimepas - 1,
                    });
                }
            });
    }

    render() {
        return (
            <div >
                <Appreciation
                    jaimeClass={this.state.jaimeClass}
                    jaimePasClass={this.state.jaimePasClass}
                    favorisClass={this.state.favorisClass}
                    hasfavoris={false}
                    vues={this.state.vues}
                    jaimes={this.state.jaimes}
                    jaimepas={this.state.jaimepas}
                    comentaires={this.state.comentaires}
                    color="event"
                     onJaimesClick={() => this.handleJaimesClick()}
                    onJaimePasClick={() => this.handleJaimePasClick()}
                    onFavorisClick={() => this.handleFavorisClick()}
                />
            </div>
        );
    }
}

export default AppreciationEvent;


