import React, { InvalidEvent } from 'react';
import axios from 'axios';

class Top5 extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            tickets: [],
            loading: false,
            nom: '',
            email: '',
            tel: '',
            prix: 0,
            type: null,
            nombre: 1,
            ticketId: null,
            total: 0,
            errors: {
                name: null,
                email: null,
                tel: null,
                nombre: null
            }

        };

        this.handleTicketSubmit = this.handleTicketSubmit.bind(this);
        this.handleShowPaiement = this.handleShowPaiement.bind(this);
    }

    componentDidMount() {

        /*fetch('/' + window.locale + '/event/json_event_tickets/' + window.eventEventId)
            .then(response => response.json())
            .then(entries => {
                //console.log('tickets...', entries)
                this.setState({
                    tickets: entries,
                    loading: true
                });

            });

        const script = document.createElement("script");
        script.src = "https://www.wecashup.com/library/MobileMoney.js";
        script.async = true;
        document.body.appendChild(script);*/
    }

    handleTicketScroll(e) {
        e.preventDefault();
        e.stopPropagation();
        $('.lst-elm-ticket').addClass('bounceInDown2').removeClass('d-none');
    }


    handlePaiementClick(e) {
        $('#WCUpaymentButton').click();
    }



    handleTicketAction(e, id, prix, type) {

        this.setState({ prix: prix });
        this.setState({ ticketId: id });
        this.setState({ type: type });
        this.setState({ nombre: 1 });

        e.preventDefault();
        e.stopPropagation();
        $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');


        if (window.userId > 0 && prix == 0) {//si le user est connecté
            axios.post('/' + window.locale + '/event/json_manage_ticket/' + id, {
                prix: this.state.prix,
            }).then(response => {
                this.setState({ nombre: 1 });

                //console.log('succes....',response.data.message)
                let message = response.data.message;
                $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');
                //$(this).parent().parent().addClass('d-none b-tkt');
                $('.cont-tik').removeClass('siut').addClass('d-none');

                $('.cont-all-plst').append('<div className="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i className="fa fa-thumbs-up mr-2 text-success"></i>Votre demande a été prise en compte. <br><span className="fs-12 text-white-50">NB: ' + message + '.</span><div className="text-right"> <button type="button" className="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
            }).catch(error => {
                this.setState({ nombre: 1 });
                let errorObject = JSON.parse(JSON.stringify(error));
                let message = errorObject.response.data.message;

                $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');
                //$(this).parent().parent().addClass('d-none b-tkt');
                $('.cont-tik').removeClass('siut').addClass('d-none');

                $('.cont-all-plst').append('<div className="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i className="fa fa-times mr-2 text-danger"></i> Votre demande n\'a pas été effectuée. <br><span className="fs-12 text-white-50">' + message + '</span><div className="text-right"> <button type="button" className="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
            }
            );
        } else {
            $('.cont-tik').removeClass('d-none sout').addClass('siut');
        }

    }

    handleShowPaiement(e) {
        //console.log('nianag....................')
        const errors = this.validate(this.state.nom, this.state.tel, this.state.email, this.state.nombre);
        if (errors) {
            // do something with errors
            return;
        }

        $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');
        //$(this).parent().parent().addClass('d-none b-tkt');
        $('.cont-tik').removeClass('siut').addClass('d-none');
        //on acitve la dialogue du paiement
        $('.cont-pay').removeClass('d-none sout').addClass('siut');
    }

    handleTicketSubmit(e) {

        console.log('je sousmet le form')
        const errors = this.validate(this.state.nom, this.state.tel, this.state.email, this.state.nombre);
        if (errors) {
            // do something with errors
            return;
        }


        axios.post('/' + window.locale + '/event/json_manage_ticket/' + this.state.ticketId, {
            email: this.state.email,
            nom: this.state.nom,
            tel: this.state.tel,
            nombre: this.state.nombre,
            prix: this.state.prix,
        }).then(response => {
            this.setState({ nombre: 1 });

            //console.log('succes....',response.data.message)
            let message = response.data.message;
            $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');
            //$(this).parent().parent().addClass('d-none b-tkt');
            $('.cont-tik').removeClass('siut').addClass('d-none');
            $('.cont-pay').removeClass('siut').addClass('d-none');

            $('.cont-all-plst').append('<div className="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i className="fa fa-thumbs-up mr-2 text-success"></i>Votre demande a été prise en compte. <br><span className="fs-12 text-white-50">NB: ' + message + '.</span><div className="text-right"> <button type="button" className="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
        }).catch(error => {
            console.log('error....', error)
            this.setState({ nombre: 1 });
            let errorObject = JSON.parse(JSON.stringify(error));
            let message = errorObject.response.data.message;

            $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');
            //$(this).parent().parent().addClass('d-none b-tkt');
            $('.cont-tik').removeClass('siut').addClass('d-none');
            $('.cont-pay').removeClass('siut').addClass('d-none');

            $('.cont-all-plst').append('<div className="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i className="fa fa-times mr-2 text-danger"></i> Votre demande n\'a pas été effectuée. <br><span className="fs-12 text-white-50">' + message + '</span><div className="text-right"> <button type="button" className="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
        }
        );
    }

    handleContentChange(e) {
        const name = e.target.name;
        const value = e.target.value;
        this.setState({ [name]: value });
    }

    validate(name, tel, email, nombre) {
        let token = null;
        let errors = {};


        if (this.state.prix > 0) {//ticket payant
            if (nombre <= 0) {
                errors.nombre = " :Le nombre de tickets doit etre au  moins 1";
                token = "errors";
            }

            if (window.userId == 0) {//invite
                if (name.length === 0) {
                    errors.name = " :Le nom ne doit pas etre vide";
                    token = "errors";
                }

                if (!(/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[A-Za-z]+$/.test(email))) {
                    errors.email = " :Le mail n'est pas valide";
                    token = "errors";
                }
            }
        } else { //ticket gratuit
            if (window.userId == 0) {//invite
                if (name.length === 0) {
                    errors.name = " :Le nom ne doit pas etre vide";
                    token = "errors";
                }

                if (tel.length === 0) {
                    errors.tel = " :Veuillez renseigner votre téléphone";
                    token = "errors";
                }


                if (!(/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[A-Za-z]+$/.test(email))) {
                    errors.email = " :Le mail n'est pas valide";
                    token = "errors";
                }
            }
        }

        this.setState({ errors: errors });
        return token;
    }


    render() {
        const website = `http://${window.location.hostname}:${window.location.port}/`;
        const urlpay = website + window.locale + '/pay/wecashup';
        const logo = website + 'kossa/img/kossa.png';

        return (
            <div >
                <div className="p-2 mt-4 mr-md-2 mb-2 d-none d-md-block">
                    <div className=" font-weight-bold text-black-50">
                        <span className="badge purple  pl-2 pr-3 p-2"><span className="mr-2"><i className="fa fa-star"></i></span> Top-5</span>
                    </div>
                </div>
                <div className="p-0 mr-md-2 fs-13  d-none d-md-block lh-x">
                    <div className="position-relative tp-lst">
                        <div className="cl-n2 w-100 cur-def pl-3" title="Stanlay enow-Hé pére">
                            <div className="position-absolute" style={{top:'5px',left: '10px'}}>1.</div>
                            Stanlay enow<div className="font-weight-bold text-truncate">Hé pére</div>
                        </div>
                        <div className="position-absolute h-100 tp-act d-none " style={{right: '0px', bottom: '0px'}}>
                            <span className="btn-play-tp-5 cur-pointer  bnt-plyay-mus trs-02 toile-bl h-100 pl-3 pr-3 d-inline-flex align-items-center tp5-pl" data-toggle="tooltip" data-placement="bottom" title="Play">
                                <i className="fas fa-play fs-12"></i>
                            </span><span className="btn-addlst-tp-5 cur-pointer ret-plst trs-02 toile-bl h-100 pl-3 pr-3 d-inline-flex align-items-center tp5-pl" data-toggle="tooltip" data-placement="bottom" title="Rétirer de la playListe">
                                <i className="fas fa-trash fs-12"></i>
                            </span>
                        </div>
                    </div>
                    <div className=" position-relative tp-lst tp-lst-elm ">
                        <div className="cl-n2 w-100 cur-def pl-3" title="Blanche bailly-Je ne suis pas ton bonbon bonbon">
                            <div className="position-absolute" style={{top:'5px',left: '10px'}}>2.</div>
                            Blanche bailly <div className="font-weight-bold text-truncate">Je ne suis pas ton bonbon bonbon</div>
                        </div>
                        <div className="position-absolute h-100 tp-act d-none " style={{right: '0px', bottom: '0px'}}>
                            <span className="btn-play-tp-5 cur-pointer bnt-plyay-mus trs-02 toile-bl h-100 pl-3 pr-3 d-inline-flex align-items-center tp5-pl" data-toggle="tooltip" data-placement="bottom" title="Play">
                                <i className="fas fa-play fs-12"></i>
                            </span><span className="btn-addlst-tp-5 cur-pointer btn-plst2 trs-02 toile-bl h-100 pl-3 pr-3 d-inline-flex align-items-center tp5-pl" data-toggle="tooltip" data-placement="bottom" title="Ajouter à la playListe">
                                <i className="fas fa-plus fs-12"></i>
                            </span>
                        </div>
                        <div className="position-absolute p-2 z-depth-1 white p-lst d-none cont-plst animated" alb-sng="Le single" style={{right:'0px'}}>

                        </div>
                    </div>
                    <div className="position-relative tp-lst">
                        <div className="cl-n2 w-100 cur-def pl-3" title="Charlote dipanda-Ndollo">
                            <div className="position-absolute" style={{top:'5px',left: '10px'}}>3.</div>
                            Charlote dipanda<div className="font-weight-bold text-truncate">Ndollo</div>
                        </div>
                        <div className="position-absolute h-100 tp-act d-none " style={{right: '0px', bottom: '0px'}}>
                            <span className="btn-play-tp-5 cur-pointer  bnt-plyay-mus trs-02 toile-bl h-100 pl-3 pr-3 d-inline-flex align-items-center tp5-pl" data-toggle="tooltip" data-placement="bottom" title="Play">
                                <i className="fas fa-play fs-12"></i>
                            </span><span className="btn-addlst-tp-5 ret-plst cur-pointer trs-02 toile-bl h-100 pl-3 pr-3 d-inline-flex align-items-center tp5-pl" data-toggle="tooltip" data-placement="bottom" title="Rétirer la playListe">
                                <i className="fas fa-trash fs-12"></i>
                            </span>
                        </div>
                    </div>
                    <div className=" position-relative tp-lst tp-lst-elm ">
                        <div className="cl-n2 w-100 cur-def pl-3" title="X-maleya -Sommet">
                            <div className="position-absolute" style={{top:'5px',left: '10px'}}>4.</div>
                            X-maleya <div className="font-weight-bold text-truncate">Sommet</div>
                        </div>
                        <div className="position-absolute h-100 tp-act d-none " style={{right: '0px', bottom: '0px'}}>
                            <span className="btn-play-tp-5 cur-pointer bnt-plyay-mus trs-02 toile-bl h-100 pl-3 pr-3 d-inline-flex align-items-center tp5-pl" data-toggle="tooltip" data-placement="bottom" title="Play">
                                <i className="fas fa-play fs-12"></i>
                            </span><span className="btn-addlst-tp-5 cur-pointer btn-plst2 trs-02 toile-bl h-100 pl-3 pr-3 d-inline-flex align-items-center tp5-pl" data-toggle="tooltip" data-placement="bottom" title="Ajouter à la playListe">
                                <i className="fas fa-plus fs-12"></i>
                            </span>
                        </div>
                        <div className="position-absolute p-2 z-depth-1 white p-lst d-none cont-plst animated" alb-sng="Le single" style={{right:'0px'}}>
                        </div>
                    </div>
                    <div className="position-relative tp-lst">
                        <div className="cl-n2 w-100 cur-def pl-3" title="Nabilla-Mon précieux">
                            <div className="position-absolute" style={{top:'5px', left: '10px'}}>5.</div>
                            Nabilla<div className="font-weight-bold text-truncate">Mon précieux</div>
                        </div>
                        <div className="position-absolute h-100 tp-act d-none " style={{right: '0px', bottom: '0px'}}>
                            <span className="btn-play-tp-5 cur-pointer bnt-plyay-mus trs-02 toile-bl h-100 pl-3 pr-3 d-inline-flex align-items-center tp5-pl" data-toggle="tooltip" data-placement="bottom" title="Play">
                                <i className="fas fa-play fs-12"></i>
                            </span><span className="btn-addlst-tp-5 cur-pointer btn-plst2 trs-02 toile-bl h-100 pl-3 pr-3 d-inline-flex align-items-center tp5-pl" data-toggle="tooltip" data-placement="bottom" title="Ajouter à la playListe">
                                <i className="fas fa-plus fs-12"></i>
                            </span>
                        </div>
                        <div className="position-absolute p-2 z-depth-1 white p-lst d-none cont-plst animated" alb-sng="Le single" style={{right:'0px'}}>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default Top5;



















